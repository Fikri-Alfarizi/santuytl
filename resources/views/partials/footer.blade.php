<footer class="bg-dark text-light py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5><i class="fas fa-gamepad me-2"></i>Game Hub</h5>
                <p>Platform komunitas game dengan download gratis tanpa iklan untuk member VIP.</p>
            </div>
            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}" class="text-light">Home</a></li>
                    <li><a href="{{ route('games.index') }}" class="text-light">Games</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-light">Blog</a></li>
                    <li><a href="{{ route('events.index') }}" class="text-light">Events</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Connect With Us</h5>
                <div class="d-flex">
                    <a href="#" class="text-light me-3"><i class="fab fa-discord fa-2x"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-youtube fa-2x"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-twitter fa-2x"></i></a>
                    <a href="#" class="text-light"><i class="fab fa-facebook fa-2x"></i></a>
                </div>
            </div>
        </div>
        <hr class="bg-light">
        <div class="text-center">
            <p>&copy; {{ date('Y') }} Game Hub. All rights reserved.</p>
        </div>
    </div>
</footer>
