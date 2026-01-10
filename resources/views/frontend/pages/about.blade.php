@extends('frontend.layouts.main')
@section('content')
<section class="bg-primary text-white text-center mt-5 py-5">
    <div class="container">
      <h1 class="display-5 fw-bold">About Adama SafeCity</h1>
      <p class="lead">Empowering communities through transparent crime reporting and proactive safety tracking.</p>
    </div>

    <div class="container">
      <div class="row g-5 align-items-center">
        <div class="col-md-6">
          <img src="https://portal.adamacity.gov.et/gallery_images/1737739854.jpg" width="500" height="500" class="img-fluid rounded shadow" alt="About Adama SafeCity">
        </div>
        <div class="col-md-6">
          <h2 class="fw-bold">Who We Are</h2>
          <p>
           Adama SafeCity is a civic-tech platform designed to help citizens report crimes easily and anonymously,
            while enabling authorities to track and manage incidents in real time. We work with law enforcement,
            NGOs, and local communities to create a safer urban environment.
          </p>
          <p>
            Our mission is to promote safety, accountability, and transparency in cities through data-driven technology.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Vision & Mission -->
  <section class="bg-light py-5">
    <div class="container text-center">
      <h2 class="fw-bold mb-4">Our Mission & Vision</h2>
      <div class="row g-4">
        <div class="col-md-6">
          <div class="p-4 bg-white shadow rounded">
            <h5 class="fw-bold">🌍 Vision</h5>
            <p>
              To build safer, more informed communities by bridging the gap between citizens and law enforcement using technology.
            </p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="p-4 bg-white shadow rounded">
            <h5 class="fw-bold">🎯 Mission</h5>
            <p>
              To provide a reliable, easy-to-use platform that encourages public participation in crime reporting and enhances incident response.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
  @endsection
