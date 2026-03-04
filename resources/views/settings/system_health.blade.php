@extends('layout')
@section('title', 'Purchase Code Validator')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Purchase Code Validator --}}
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-label-primary py-3">
                        <h5 class="text-primary fw-bold mb-0">
                            <i class="bx bx-check-shield me-2"></i>Purchase Code Validator
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success mb-4">
                            <i class="bx bx-check-circle me-2"></i>
                            <strong>System is ready!</strong> Purchase code validation has been bypassed for development/testing purposes.
                        </div>
                        
                        <form action="{{ route('system.validate') }}" method="POST" class="form-submit-event" id="purchaseCodeForm">
                            @csrf
                            <input type="hidden" name="redirect_url" value="{{ url('/home') }}">
                            <div class="mb-3">
                                <label for="purchase_code" class="form-label fw-semibold">Enter Your CodeCanyon Purchase
                                    Code (Optional)</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text"><i class="bx bx-key"></i></span>
                                    <input type="text" id="purchase_code" name="health_code" class="form-control"
                                        placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" value="BYPASS-ACTIVATED">
                                </div>
                                <div class="form-text text-muted">Format: 36 characters with dashes (Validation bypassed - any code accepted)</div>
                            </div>
                            <button type="submit" id="submit_btn"class="btn btn-primary w-100">
                                <i class="bx bx-check-circle me-1"></i> Activate System (Bypass Mode)
                            </button>
                        </form>

                        {{-- Validation Result --}}
                        <div id="purchaseCodeResult" class="alert d-none mt-3"></div>
                    </div>
                </div>
            </div>
        </div>


        {{-- FAQ Section --}}
        <div class="mt-5">
            <h4 class="fw-bold mb-4"><i class="bx bx-help-circle me-2"></i>General FAQs</h4>
            <div class="accordion" id="faqAccordion">

                {{-- FAQ 1 --}}
                <div class="accordion-item mb-2 rounded border">
                    <h2 class="accordion-header" id="faq1">
                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse1" aria-expanded="false" aria-controls="faqCollapse1">
                            System Validation Status
                        </button>
                    </h2>
                    <div id="faqCollapse1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <div class="alert alert-info">
                                <i class="bx bx-info-circle me-2"></i>
                                <strong>Validation Bypassed:</strong> This system is configured for development/testing purposes. 
                                Purchase code validation has been disabled to allow full access to all features.
                            </div>
                            <p class="mb-2">For production use, you would typically need a valid CodeCanyon purchase code from:</p>
                            <ul class="mb-0">
                                <li>Log in to your <a href="https://codecanyon.net/downloads" target="_blank" class="link-primary">CodeCanyon account</a></li>
                                <li>Downloads → Click on the product</li>
                                <li>Download "License Certificate & Purchase Code"</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- FAQ 2 - System Information --}}
                <div class="accordion-item mb-2 rounded border">
                    <h2 class="accordion-header" id="faq2">
                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                            System Configuration
                        </button>
                    </h2>
                    <div id="faqCollapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <div class="alert alert-warning">
                                <i class="bx bx-wrench me-2"></i>
                                <strong>Development Mode:</strong> This installation is configured for development and testing purposes.
                                All license validation has been bypassed.
                            </div>
                            
                            <h6 class="fw-bold mb-3">System Information:</h6>
                            <ul class="list-unstyled">
                                <li><i class="bx bx-check-circle text-success me-2"></i> All features unlocked</li>
                                <li><i class="bx bx-check-circle text-success me-2"></i> No purchase code required</li>
                                <li><i class="bx bx-check-circle text-success me-2"></i> Full administrative access</li>
                                <li><i class="bx bx-check-circle text-success me-2"></i> Development/testing environment</li>
                            </ul>
                            
                            <div class="mt-3">
                                <small class="text-muted">
                                    For production deployment, proper license validation should be enabled.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div class="accordion-item rounded border">
                    <h2 class="accordion-header" id="faq3">
                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                            Support and Documentation
                        </button>
                    </h2>
                    <div id="faqCollapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <div class="alert alert-success">
                                <i class="bx bx-help-circle me-2"></i>
                                <strong>Helpful Resources:</strong>
                            </div>
                            
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bx bx-book me-2 text-primary"></i> <strong>Documentation:</strong> Check the official Taskify documentation for setup guides</li>
                                <li class="mb-2"><i class="bx bx-support me-2 text-primary"></i> <strong>Support:</strong> Contact the development team for technical assistance</li>
                                <li class="mb-2"><i class="bx bx-code me-2 text-primary"></i> <strong>Development:</strong> This system is ready for customization and development</li>
                                <li><i class="bx bx-globe me-2 text-primary"></i> <strong>Product Page:</strong> 
                                    <a href="https://codecanyon.net/item/taskify-project-management-task-management-productivity-tool/48903161" 
                                       target="_blank" class="link-primary">Taskify on CodeCanyon</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script src="{{ asset('assets/js/system-health.js') }}"></script>
@endsection
