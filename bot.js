// bot.js - Discord bot minimal untuk integrasi Laravel (ESM)
import 'dotenv/config';

import { Client, GatewayIntentBits } from 'discord.js';
import axios from 'axios';
import express from 'express';
import {
  joinVoiceChannel,
  createAudioPlayer,
  createAudioResource,
  AudioPlayerStatus,
  VoiceConnectionStatus,
  entersState
} from '@discordjs/voice';

const app = express();
app.use(express.json());

// Voice connection state
let voiceConnection = null;
let voiceChannelId = null;
let voiceGuildId = null;
let voiceConnectedAt = null;

const client = new Client({
  intents: [
    GatewayIntentBits.Guilds,
    GatewayIntentBits.GuildMessages,
    GatewayIntentBits.MessageContent,
    GatewayIntentBits.GuildMembers,
    GatewayIntentBits.GuildVoiceStates
  ]
});

const WEBHOOK_URL = process.env.WEBHOOK_URL || 'http://127.0.0.1:8000/api/discord/webhook';
const WEBHOOK_SECRET = process.env.DISCORD_BOT_SECRET;
const GUILD_ID = process.env.DISCORD_GUILD_ID;

client.once('ready', () => {
  console.log(`Bot ${client.user.tag} sudah online!`);
});

// CORS middleware - allow requests from Laravel frontend
app.use((req, res, next) => {
  res.header('Access-Control-Allow-Origin', '*');
  res.header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
  res.header('Access-Control-Allow-Headers', 'Content-Type, Authorization');

  // Handle preflight requests
  if (req.method === 'OPTIONS') {
    return res.sendStatus(200);
  }

  next();
});

// Get list of voice channels
app.get('/voice/channels', async (req, res) => {
  try {
    let guild;
    if (GUILD_ID) {
      try {
        guild = await client.guilds.fetch(GUILD_ID);
      } catch (e) {
        console.error(`Failed to fetch guild from env ID ${GUILD_ID}:`, e.message);
      }
    }

    if (!guild) {
      guild = client.guilds.cache.first();
    }

    if (!guild) {
      console.error('No guild found for bot');
      return res.json({ channels: [] });
    }

    await guild.channels.fetch(); // Ensure channels are fetched

    const voiceChannels = guild.channels.cache
      .filter(channel => channel.type === 2)
      .map(channel => ({
        id: channel.id,
        name: channel.name,
        members: channel.members.size
      }));

    res.json({ channels: voiceChannels });
  } catch (err) {
    console.error('Error in /voice/channels:', err);
    res.status(500).json({ error: err.message });
  }
});

// Get list of all guilds (servers) where bot is present
app.get('/guilds', async (req, res) => {
  try {
    const guilds = client.guilds.cache.map(guild => ({
      id: guild.id,
      name: guild.name,
      icon: guild.icon ? `https://cdn.discordapp.com/icons/${guild.id}/${guild.icon}.png` : null,
      memberCount: guild.memberCount,
      ownerId: guild.ownerId
    }));

    res.json({ guilds });
  } catch (err) {
    console.error('Error in /guilds:', err);
    res.status(500).json({ error: err.message });
  }
});

// Get server statistics for community dashboard
app.get('/server-stats', async (req, res) => {
  try {
    const { guild_id } = req.query;
    let guild;

    if (guild_id) {
      try {
        guild = await client.guilds.fetch(guild_id);
      } catch (e) {
        return res.status(404).json({ error: 'Guild tidak ditemukan' });
      }
    } else {
      guild = GUILD_ID ? await client.guilds.fetch(GUILD_ID) : client.guilds.cache.first();
    }

    if (!guild) {
      return res.status(404).json({ error: 'No guild found' });
    }

    // Fetch all members to get accurate online count
    await guild.members.fetch();

    // Count online members
    const onlineCount = guild.members.cache.filter(member =>
      member.presence?.status === 'online' ||
      member.presence?.status === 'idle' ||
      member.presence?.status === 'dnd'
    ).size;

    // Fetch channels and roles
    await guild.channels.fetch();
    await guild.roles.fetch();

    const stats = {
      guild_id: guild.id,
      guild_name: guild.name,
      guild_icon: guild.icon ? `https://cdn.discordapp.com/icons/${guild.id}/${guild.icon}.png` : null,
      member_count: guild.memberCount,
      online_count: onlineCount,
      boost_level: guild.premiumTier,
      boost_count: guild.premiumSubscriptionCount || 0,
      channel_count: guild.channels.cache.size,
      text_channel_count: guild.channels.cache.filter(c => c.type === 0).size,
      voice_channel_count: guild.channels.cache.filter(c => c.type === 2).size,
      role_count: guild.roles.cache.size - 1, // Exclude @everyone
      created_at: guild.createdAt.toISOString(),
      owner_id: guild.ownerId
    };

    res.json(stats);
  } catch (err) {
    console.error('Error in /server-stats:', err);
    res.status(500).json({ error: err.message });
  }
});

// Get scheduled events from Discord for Event Auto-Sync
app.get('/events', async (req, res) => {
  try {
    const { guild_id } = req.query;
    let guild;

    if (guild_id) {
      try {
        guild = await client.guilds.fetch(guild_id);
      } catch (e) {
        return res.status(404).json({ error: 'Guild not found' });
      }
    } else {
      guild = GUILD_ID ? await client.guilds.fetch(GUILD_ID) : client.guilds.cache.first();
    }

    if (!guild) {
      return res.status(404).json({ error: 'No guild found' });
    }

    await guild.scheduledEvents.fetch();

    const events = guild.scheduledEvents.cache.map(event => ({
      id: event.id,
      name: event.name,
      description: event.description,
      scheduled_start_time: event.scheduledStartTimestamp,
      scheduled_end_time: event.scheduledEndTimestamp,
      status: event.status, // e.g., SCHEDULED, ACTIVE, COMPLETED, CANCELED
      image: event.image ? `https://cdn.discordapp.com/guilds/${guild.id}/scheduled-events/${event.id}/${event.image}.png` : null,
      creator_id: event.creatorId,
      entity_type: event.entityType, // e.g., EXTERNAL, VOICE, STAGE_INSTANCE
      url: event.url,
      channel_id: event.channelId,
    }));

    res.json({ events });
  } catch (err) {
    console.error('Error in /events:', err);
    res.status(500).json({ error: err.message });
  }
});

const userMessageCooldown = new Map();

client.on('messageCreate', async (message) => {
  if (message.author.bot || !message.guild) return;

  // --- Sistem Koin ---
  const now = Date.now();
  const cooldownAmount = 60 * 1000; // 1 menit cooldown
  if (userMessageCooldown.has(message.author.id)) {
    const expirationTime = userMessageCooldown.get(message.author.id) + cooldownAmount;
    if (now < expirationTime) {
      // Masih dalam cooldown, jangan lakukan apa-apa
    } else {
      // Cooldown berakhir, beri koin
      userMessageCooldown.set(message.author.id, now);
      const coinsToAdd = Math.floor(Math.random() * 5) + 1; // Random 1-5 koin
      try {
        await axios.post('http://127.0.0.1:8000/api/add-coins', {
          discord_id: message.author.id,
          coins: coinsToAdd
        }, {
          headers: { 'X-Discord-Bot-Secret': WEBHOOK_SECRET }
        });
      } catch (error) {
        console.error(`Gagal menambahkan koin untuk ${message.author.tag}:`, error.message);
      }
    }
  } else {
    // Pengguna belum ada di map, tambahkan dan beri koin
    userMessageCooldown.set(message.author.id, now);
    const coinsToAdd = Math.floor(Math.random() * 5) + 1;
    try {
      await axios.post('http://127.0.0.1:8000/api/add-coins', {
        discord_id: message.author.id,
        coins: coinsToAdd
      }, {
        headers: { 'X-Discord-Bot-Secret': WEBHOOK_SECRET }
      });
    } catch (error) {
      console.error(`Gagal menambahkan koin untuk ${message.author.tag}:`, error.message);
    }
  }

  // 1. Kirim aktivitas
  axios.post(WEBHOOK_URL, {
    discord_id: message.author.id,
    event_type: 'user_activity'
  }, {
    headers: { 'X-Discord-Bot-Secret': WEBHOOK_SECRET }
  }).catch(() => { });

  // 2. Kirim XP & role jika pesan bermakna
  if (message.content.split(' ').length > 10) {
    // XP
    await axios.post(WEBHOOK_URL, {
      discord_id: message.author.id,
      event_type: 'xp_update',
      xp: 5
    }, {
      headers: { 'X-Discord-Bot-Secret': WEBHOOK_SECRET }
    }).catch(() => { });

    // Role (urutkan dari tertinggi ke terendah)
    const member = await message.guild.members.fetch(message.author.id);
    const allRoles = member.roles.cache
      .filter(r => r.name !== '@everyone')
      .sort((a, b) => b.position - a.position)
      .map(r => ({
        name: r.name,
        color: r.hexColor,
        emoji: r.unicodeEmoji || ''
      }));
    await axios.post(WEBHOOK_URL, {
      discord_id: message.author.id,
      event_type: 'role_update',
      new_role: member.roles.highest.name,
      all_roles: allRoles
    }, {
      headers: { 'X-Discord-Bot-Secret': WEBHOOK_SECRET }
    }).catch(() => { });
  }

  // 3. Sinkronisasi Game & Event dari channel Discord
  // Ganti ID_CHANNEL_GAME dan ID_CHANNEL_EVENT sesuai kebutuhan
  const GAME_CHANNEL_ID = process.env.DISCORD_GAME_CHANNEL_ID || '1391274558514004019';
  const EVENT_CHANNEL_ID = process.env.DISCORD_EVENT_CHANNEL_ID || '1439538769148772372';
  const API_GAME_URL = process.env.API_GAME_URL || 'http://127.0.0.1:8000/api/discord/game';
  const API_EVENT_URL = process.env.API_EVENT_URL || 'http://127.0.0.1:8000/api/discord/event';

  // Game upload (contoh: link premium di channel game)
  if (message.channel.id === GAME_CHANNEL_ID) {
    // Parsing sederhana: [judul]\n[link]\n[deskripsi]
    const lines = message.content.split('\n');
    const title = lines[0] || 'Game Baru';
    const link = lines[1] || '';
    const description = lines.slice(2).join('\n');
    let image = null;
    if (message.attachments.size > 0) {
      image = message.attachments.first().url;
    }
    await axios.post(API_GAME_URL, {
      title,
      link,
      description,
      image,
      discord_message_id: message.id
    }).catch((err) => {
      console.error('Gagal sync game:', err.message);
    });
  }

  // Event info (contoh: event baru di channel event)
  if (message.channel.id === EVENT_CHANNEL_ID) {
    // Parsing sederhana: [judul]\n[tanggal]\n[deskripsi]
    const lines = message.content.split('\n');
    const title = lines[0] || 'Event Baru';
    const date = lines[1] || null;
    const description = lines.slice(2).join('\n');
    let image = null;
    if (message.attachments.size > 0) {
      image = message.attachments.first().url;
    }
    await axios.post(API_EVENT_URL, {
      title,
      date,
      description,
      image,
      discord_message_id: message.id
    }).catch((err) => {
      console.error('Gagal sync event:', err.message);
    });
  }
});

// Script migrasi: fetch semua pesan lama dari channel game dan kirim ke Laravel
async function migrateAllGamesFromChannel() {
  const GAME_CHANNEL_ID = process.env.DISCORD_GAME_CHANNEL_ID || '1391274558514004019';
  const API_GAME_URL = process.env.API_GAME_URL || 'http://127.0.0.1:8000/api/discord/game';
  const channel = await client.channels.fetch(GAME_CHANNEL_ID);
  if (!channel || channel.type !== 0) {
    console.error('Channel game tidak ditemukan atau bukan text channel');
    return;
  }
  let lastId = undefined;
  let total = 0;
  while (true) {
    const options = { limit: 100 };
    if (lastId) options.before = lastId;
    const messages = await channel.messages.fetch(options);
    if (messages.size === 0) break;
    for (const message of messages.values()) {
      if (message.author.bot) continue;
      // Parsing sesuai format
      const lines = message.content.split('\n');
      const title = lines[0] || 'Game Baru';
      const link = lines.find(l => l.includes('http')) || '';
      const description = lines.filter(l => l !== title && !l.includes('http')).join('\n');
      let image = null;
      if (message.attachments.size > 0) {
        image = message.attachments.first().url;
      }
      try {
        await axios.post(API_GAME_URL, {
          title,
          link,
          description,
          image,
          discord_message_id: message.id
        });
        total++;
        console.log('Migrated:', title);
      } catch (err) {
        console.error('Gagal migrasi game:', err.message);
      }
    }
    lastId = messages.last().id;
    if (messages.size < 100) break;
  }
  console.log('Migrasi selesai. Total:', total);
}

// Jalankan migrasi manual jika diinginkan
if (process.argv.includes('--migrate-games')) {
  client.once('ready', async () => {
    await migrateAllGamesFromChannel();
    process.exit(0);
  });
}

// Script migrasi: fetch semua pesan lama dari channel event dan kirim ke Laravel
async function migrateAllEventsFromChannel() {
  const EVENT_CHANNEL_ID = process.env.DISCORD_EVENT_CHANNEL_ID || '1439538769148772372';
  const API_EVENT_URL = process.env.API_EVENT_URL || 'http://127.0.0.1:8000/api/discord/event';
  const channel = await client.channels.fetch(EVENT_CHANNEL_ID);
  if (!channel || channel.type !== 0) {
    console.error('Channel event tidak ditemukan atau bukan text channel');
    return;
  }
  let lastId = undefined;
  let total = 0;
  while (true) {
    const options = { limit: 100 };
    if (lastId) options.before = lastId;
    const messages = await channel.messages.fetch(options);
    if (messages.size === 0) break;
    for (const message of messages.values()) {
      if (message.author.bot) continue;
      // Parsing sesuai format: [judul]\n[tanggal]\n[deskripsi]
      const lines = message.content.split('\n');
      const title = lines[0] || 'Event Baru';
      const date = lines[1] || null;
      const description = lines.slice(2).join('\n');
      let image = null;
      if (message.attachments.size > 0) {
        image = message.attachments.first().url;
      }
      try {
        await axios.post(API_EVENT_URL, {
          title,
          date,
          description,
          image,
          discord_message_id: message.id
        });
        total++;
        console.log('Migrated event:', title);
      } catch (err) {
        console.error('Gagal migrasi event:', err.message);
      }
    }
    lastId = messages.last().id;
    if (messages.size < 100) break;
  }
  console.log('Migrasi event selesai. Total:', total);
}

// Jalankan migrasi event manual jika diinginkan
if (process.argv.includes('--migrate-events')) {
  client.once('ready', async () => {
    await migrateAllEventsFromChannel();
    process.exit(0);
  });
}

const channelId = '1385912786395336875';

async function fetchAllMessages(channel) {
  let messages = [];
  let lastId;
  while (true) {
    const options = { limit: 100 };
    if (lastId) options.before = lastId;
    const fetched = await channel.messages.fetch(options);
    if (fetched.size === 0) break;
    messages = messages.concat(Array.from(fetched.values()));
    lastId = fetched.last().id;
    if (fetched.size < 100) break;
  }
  return messages;
}

// Contoh endpoint Express
app.get('/game-requests', async (req, res) => {
  try {
    const channel = await client.channels.fetch(channelId);
    const messages = await fetchAllMessages(channel);
    // Ubah sesuai format yang Anda butuhkan
    const data = messages.map(msg => ({
      id: msg.id,
      author: msg.author.username,
      content: msg.content,
      timestamp: msg.createdTimestamp
    }));
    res.json({ requests: data });
  } catch (err) {
    res.status(500).json({ requests: [] });
  }
});

// Endpoint kirim pesan ke channel Discord
app.post('/send-message', async (req, res) => {
  const { channel_id, message } = req.body;
  if (!channel_id || !message) return res.status(400).json({ error: 'channel_id dan message wajib diisi' });
  try {
    const channel = await client.channels.fetch(channel_id);
    if (!channel || !channel.send) {
      return res.status(404).json({ error: 'Channel tidak ditemukan atau tidak bisa mengirim pesan' });
    }
    await channel.send(message);
    res.json({ success: true });
  } catch (err) {
    console.error('Gagal mengirim pesan ke Discord:', err);
    res.status(500).json({ error: err.message });
  }
});

app.post('/send-dm', async (req, res) => {
  const { user_id, message } = req.body;
  if (!user_id || !message) return res.status(400).json({ error: 'user_id dan message wajib diisi' });
  try {
    const user = await client.users.fetch(user_id);
    await user.send(message);
    res.json({ success: true });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// Get users from specific guild or first guild
app.get('/users', async (req, res) => {
  try {
    const { guild_id } = req.query;
    let guild;

    if (guild_id) {
      try {
        guild = await client.guilds.fetch(guild_id);
      } catch (e) {
        return res.status(404).json({ error: 'Guild tidak ditemukan' });
      }
    } else {
      guild = client.guilds.cache.first();
    }

    if (!guild) return res.json({ users: [] });

    await guild.members.fetch(); // Ensure all members are cached
    const users = guild.members.cache
      .filter(member => !member.user.bot)
      .map(member => ({
        id: member.user.id,
        username: member.user.username,
        discriminator: member.user.discriminator
      }));

    res.json({ users });
  } catch (err) {
    res.json({ users: [] });
  }
});

// Get single user details by ID
app.get('/users/:id', async (req, res) => {
  try {
    const userId = req.params.id;
    const guild = client.guilds.cache.first();
    if (!guild) return res.status(404).json({ error: 'Guild not found' });

    await guild.members.fetch();
    const member = guild.members.cache.get(userId);

    if (!member) {
      return res.status(404).json({ error: 'User not found' });
    }

    // Get user roles
    const roles = member.roles.cache
      .filter(role => role.id !== guild.id) // Exclude @everyone role
      .map(role => ({
        id: role.id,
        name: role.name,
        color: role.color.toString(16).padStart(6, '0'),
        position: role.position
      }))
      .sort((a, b) => b.position - a.position);

    const user = {
      id: member.user.id,
      username: member.user.username,
      discriminator: member.user.discriminator,
      avatar: member.user.avatar,
      joined_at: member.joinedAt,
      roles: roles,
      guild: guild.name,
      guild_id: guild.id
    };

    res.json({ user });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.post('/kick', async (req, res) => {
  const { guild_id, user_id, reason } = req.body;
  if (!guild_id || !user_id) return res.status(400).json({ error: 'guild_id dan user_id wajib diisi' });
  try {
    const guild = await client.guilds.fetch(guild_id);
    const member = await guild.members.fetch(user_id);
    await member.kick(reason || 'Kicked by admin');
    res.json({ success: true });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.post('/ban', async (req, res) => {
  const { guild_id, user_id, reason } = req.body;
  if (!guild_id || !user_id) return res.status(400).json({ error: 'guild_id dan user_id wajib diisi' });
  try {
    const guild = await client.guilds.fetch(guild_id);
    await guild.members.ban(user_id, { reason: reason || 'Banned by admin' });
    res.json({ success: true });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// Endpoint untuk mengambil daftar role dari guild tertentu atau semua guild
app.get('/roles', async (req, res) => {
  try {
    const { guild_id } = req.query;
    let roles = [];

    if (guild_id) {
      // Filter by specific guild
      try {
        const guild = await client.guilds.fetch(guild_id);
        await guild.roles.fetch();
        guild.roles.cache.forEach(role => {
          if (role.name !== '@everyone') {
            roles.push({
              id: role.id,
              name: role.name,
              guild: guild.name,
              guild_id: guild.id
            });
          }
        });
      } catch (e) {
        return res.status(404).json({ error: 'Guild tidak ditemukan' });
      }
    } else {
      // Get from all guilds
      for (const [guildId, guild] of client.guilds.cache) {
        await guild.roles.fetch();
        guild.roles.cache.forEach(role => {
          if (role.name !== '@everyone') {
            roles.push({
              id: role.id,
              name: role.name,
              guild: guild.name,
              guild_id: guild.id
            });
          }
        });
      }
    }

    res.json({ roles });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.post('/assign-role', async (req, res) => {
  const { guild_id, user_id, role_id } = req.body;
  if (!guild_id || !user_id || !role_id) return res.status(400).json({ error: 'guild_id, user_id, role_id wajib diisi' });
  try {
    const guild = await client.guilds.fetch(guild_id);
    const member = await guild.members.fetch(user_id);
    await member.roles.add(role_id);
    res.json({ success: true });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.post('/remove-role', async (req, res) => {
  const { guild_id, user_id, role_id } = req.body;
  if (!guild_id || !user_id || !role_id) return res.status(400).json({ error: 'guild_id, user_id, role_id wajib diisi' });
  try {
    const guild = await client.guilds.fetch(guild_id);
    const member = await guild.members.fetch(user_id);
    await member.roles.remove(role_id);
    res.json({ success: true });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// Get staff members (Owners and Moderators)
app.get('/staff', async (req, res) => {
  try {
    const { guild_id } = req.query;
    let guild;

    if (guild_id) {
      try {
        guild = await client.guilds.fetch(guild_id);
      } catch (e) {
        return res.status(404).json({ error: 'Guild not found' });
      }
    } else {
      guild = GUILD_ID ? await client.guilds.fetch(GUILD_ID) : client.guilds.cache.first();
    }

    if (!guild) return res.status(404).json({ error: 'No guild found' });

    await guild.members.fetch({ withPresences: true }); // Fetch members with presences for online status if needed
    await guild.roles.fetch(); // Ensure roles are fetched

    const staffRoles = ['Owner', 'Moderator', 'Admin']; // Define roles that are considered staff

    const staffMembers = guild.members.cache
      .filter(member =>
        !member.user.bot && // Exclude bots
        member.roles.cache.some(role => staffRoles.includes(role.name))
      )
      .map(member => ({
        id: member.user.id,
        username: member.user.username,
        discriminator: member.user.discriminator,
        avatar: member.user.avatarURL({ dynamic: true, size: 128 }),
        display_name: member.displayName,
        joined_at: member.joinedAt,
        roles: member.roles.cache
          .filter(role => role.id !== guild.id) // Exclude @everyone role
          .map(role => ({
            id: role.id,
            name: role.name,
            color: role.color.toString(16).padStart(6, '0'),
            position: role.position
          }))
          .sort((a, b) => b.position - a.position)
      }));

    res.json({ staff: staffMembers });
  } catch (err) {
    console.error('Error in /staff:', err);
    res.status(500).json({ error: err.message });
  }
});

// Endpoint untuk mengambil daftar channel text dari guild tertentu atau semua guild
app.get('/channels', async (req, res) => {
  try {
    const { guild_id } = req.query; // Get guild_id from query parameter
    let channels = [];

    if (guild_id) {
      // Filter by specific guild
      try {
        const guild = await client.guilds.fetch(guild_id);
        await guild.channels.fetch();
        guild.channels.cache.forEach(channel => {
          if (channel.type === 0) { // 0 = GUILD_TEXT
            channels.push({
              id: channel.id,
              name: channel.name,
              guild: guild.name,
              guild_id: guild.id
            });
          }
        });
      } catch (e) {
        return res.status(404).json({ error: 'Guild tidak ditemukan' });
      }
    } else {
      // Get from all guilds
      for (const [guildId, guild] of client.guilds.cache) {
        await guild.channels.fetch();
        guild.channels.cache.forEach(channel => {
          if (channel.type === 0) { // 0 = GUILD_TEXT
            channels.push({
              id: channel.id,
              name: channel.name,
              guild: guild.name,
              guild_id: guild.id
            });
          }
        });
      }
    }

    res.json({ channels });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// ==================== VOICE CHANNEL ENDPOINTS ====================

// Join voice channel
// Join voice channel
app.post('/voice/join', async (req, res) => {
  try {
    const { guild_id, channel_id } = req.body;

    if (!guild_id || !channel_id) {
      return res.status(400).json({ error: 'guild_id dan channel_id wajib diisi' });
    }

    let guild;
    try {
      guild = await client.guilds.fetch(guild_id);
    } catch (e) {
      return res.status(404).json({ error: 'Guild tidak ditemukan' });
    }

    let channel;
    try {
      channel = await guild.channels.fetch(channel_id);
    } catch (e) {
      return res.status(404).json({ error: 'Voice channel tidak ditemukan' });
    }

    if (!channel || channel.type !== 2) {
      return res.status(404).json({ error: 'Channel bukan voice channel' });
    }

    if (voiceConnection) {
      voiceConnection.destroy();
    }

    voiceConnection = joinVoiceChannel({
      channelId: channel_id,
      guildId: guild_id,
      adapterCreator: guild.voiceAdapterCreator,
      selfDeaf: false,
      selfMute: true
    });

    voiceChannelId = channel_id;
    voiceGuildId = guild_id;
    voiceConnectedAt = new Date();

    voiceConnection.on(VoiceConnectionStatus.Disconnected, async (oldState, newState) => {
      try {
        await Promise.race([
          entersState(voiceConnection, VoiceConnectionStatus.Signalling, 5_000),
          entersState(voiceConnection, VoiceConnectionStatus.Connecting, 5_000),
        ]);
      } catch (error) {
        if (voiceConnection) {
          voiceConnection.destroy();
          voiceConnection = null;
          // Auto reconnect logic
          setTimeout(async () => {
            if (voiceChannelId && voiceGuildId) {
              try {
                const guild = await client.guilds.fetch(voiceGuildId);
                if (guild) {
                  voiceConnection = joinVoiceChannel({
                    channelId: voiceChannelId,
                    guildId: voiceGuildId,
                    adapterCreator: guild.voiceAdapterCreator,
                    selfDeaf: false,
                    selfMute: true
                  });
                  console.log('Reconnected to voice channel');
                }
              } catch (err) {
                console.error('Failed to reconnect:', err);
              }
            }
          }, 5000);
        }
      }
    });

    voiceConnection.on(VoiceConnectionStatus.Destroyed, () => {
      console.log('Voice connection destroyed');
      voiceConnection = null;
      voiceChannelId = null;
      voiceGuildId = null;
      voiceConnectedAt = null;
    });

    res.json({ success: true, message: 'Bot berhasil join voice channel', channel: channel.name });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// Leave voice channel
app.post('/voice/leave', async (req, res) => {
  try {
    if (!voiceConnection) {
      return res.status(400).json({ error: 'Bot tidak sedang di voice channel' });
    }

    voiceConnection.destroy();
    voiceConnection = null;
    voiceChannelId = null;
    voiceGuildId = null;
    voiceConnectedAt = null;

    res.json({ success: true, message: 'Bot berhasil leave voice channel' });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// Get voice status
app.get('/voice/status', async (req, res) => {
  try {
    if (!voiceConnection) {
      return res.json({ connected: false });
    }

    let channel = null;
    let guild = null;

    if (voiceGuildId) {
      try {
        guild = await client.guilds.fetch(voiceGuildId);
        if (guild && voiceChannelId) {
          channel = await guild.channels.fetch(voiceChannelId);
        }
      } catch (e) { }
    }

    const duration = voiceConnectedAt ? Math.floor((new Date() - voiceConnectedAt) / 1000) : 0;

    res.json({
      connected: true,
      channel_id: voiceChannelId,
      channel_name: channel ? channel.name : 'Unknown',
      guild_name: guild ? guild.name : 'Unknown',
      duration: duration,
      status: voiceConnection.state.status
    });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});



app.get('/status', (req, res) => {
  res.json({ online: true, tag: client.user.tag });
});

app.listen(3001, () => {
  console.log('Bot Discord HTTP API listening on port 3001');
});

client.login(process.env.DISCORD_BOT_TOKEN);

// Endpoint menerima tiket support dari web dan kirim ke channel Discord
app.post('/tickets', async (req, res) => {
  try {
    const { ticket_id, user, subject, message } = req.body;
    const SUPPORT_CHANNEL_ID = process.env.DISCORD_SUPPORT_CHANNEL_ID || 'YOUR_SUPPORT_CHANNEL_ID';
    const guild = GUILD_ID ? await client.guilds.fetch(GUILD_ID) : client.guilds.cache.first();
    if (!guild) return res.status(404).json({ error: 'No guild found' });
    const channel = await guild.channels.fetch(SUPPORT_CHANNEL_ID);
    if (!channel || !channel.send) return res.status(404).json({ error: 'Support channel not found' });
    const discordMsg = await channel.send({
      embeds: [{
        title: `Tiket #${ticket_id}: ${subject}`,
        description: message,
        fields: [
          { name: 'User', value: user, inline: true },
          { name: 'Status', value: 'Open', inline: true }
        ],
        color: 0x7289da,
        timestamp: new Date().toISOString()
      }]
    });
    return res.json({ success: true, discord_ticket_id: discordMsg.id });
  } catch (err) {
    console.error('Error in /tickets:', err);
    res.status(500).json({ error: err.message });
  }
});

// Endpoint statistik lengkap user Discord
app.get('/user-stats/:id', async (req, res) => {
  try {
    const userId = req.params.id;
    const guild = client.guilds.cache.first();
    if (!guild) return res.status(404).json({ error: 'Guild not found' });
    await guild.members.fetch();
    const member = guild.members.cache.get(userId);
    if (!member) return res.status(404).json({ error: 'User not found' });

    // Dummy data, ganti dengan data real dari database/logic bot Anda
    const stats = {
      id: member.user.id,
      username: member.user.username,
      discriminator: member.user.discriminator,
      avatar: member.user.avatarURL({ dynamic: true, size: 128 }),
      joined_at: member.joinedAt,
      roles: member.roles.cache.filter(role => role.id !== guild.id).map(role => role.name),
      xp: Math.floor(Math.random() * 10000),
      level: Math.floor(Math.random() * 50),
      messages: Math.floor(Math.random() * 5000),
      voice_minutes: Math.floor(Math.random() * 10000),
      badges: [
        { name: 'Early Supporter', icon: '🥇' },
        { name: 'Event Winner', icon: '🏆' }
      ],
      achievements: [
        { name: 'Top 10 XP', date: '2025-10-01' },
        { name: 'Active Member', date: '2025-09-15' }
      ]
    };
    res.json({ stats });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});
