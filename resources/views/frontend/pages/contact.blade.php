@extends('frontend.layouts.main')
@section('content')
<div class="container-fluid bg-light py-5">
    <section id="contact" class="py-5">
         <div class="container">
            <h2 class="text-center fw-bold mb-4">Contact Us</h2>
             @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
        <div class="row justify-content-center">
            <div class="col-md-6 bg-white">
                 <form method="POST" action="{{ route('contact.store') }}">
        @csrf                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" required>
                                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror

                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Your Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror

                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" id="subject" name="subject" class="form-control" placeholder="Enter a subject" required>
                                    @error('subject') <small class="text-danger">{{ $message }}</small> @enderror

                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea id="message" class="form-control" name="message" rows="5" placeholder="Your message..." required></textarea>
                                    @error('message') <small class="text-danger">{{ $message }}</small> @enderror

                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d60006.66206478795!2d39.26443765!3d8.5339447!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x164b1f65036ecb0f%3A0x6babded8f5e67ef6!2sAdama!5e1!3m2!1sen!2set!4v1750064911301!5m2!1sen!2set" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
         </div>
    </section>
</div>
@endsection
