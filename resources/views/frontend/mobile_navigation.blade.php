<div class="mobile-navigation">
    @php
        $user = auth()->user();
		$userWallet = null;
		$userLikeBalance = null;
		
		if (!empty($user)) {
			$userLikeBalance = $user->likeBalance;
		}
    @endphp
    
    <div>
        <a href="/">
            <svg fill="#000000" width="30px" height="30px" viewBox="0 0 35 35" data-name="Layer 2"
                 id="e73e2821-510d-456e-b3bd-9363037e93e3" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.933,15.055H3.479A3.232,3.232,0,0,1,.25,11.827V3.478A3.232,3.232,0,0,1,3.479.25h8.454a3.232,3.232,0,0,1,3.228,3.228v8.349A3.232,3.232,0,0,1,11.933,15.055ZM3.479,2.75a.73.73,0,0,0-.729.728v8.349a.73.73,0,0,0,.729.728h8.454a.729.729,0,0,0,.728-.728V3.478a.729.729,0,0,0-.728-.728Z"/>
                <path d="M11.974,34.75H3.52A3.233,3.233,0,0,1,.291,31.521V23.173A3.232,3.232,0,0,1,3.52,19.945h8.454A3.232,3.232,0,0,1,15.2,23.173v8.348A3.232,3.232,0,0,1,11.974,34.75ZM3.52,22.445a.73.73,0,0,0-.729.728v8.348a.73.73,0,0,0,.729.729h8.454a.73.73,0,0,0,.728-.729V23.173a.729.729,0,0,0-.728-.728Z"/>
                <path d="M31.522,34.75H23.068a3.233,3.233,0,0,1-3.229-3.229V23.173a3.232,3.232,0,0,1,3.229-3.228h8.454a3.232,3.232,0,0,1,3.228,3.228v8.348A3.232,3.232,0,0,1,31.522,34.75Zm-8.454-12.3a.73.73,0,0,0-.729.728v8.348a.73.73,0,0,0,.729.729h8.454a.73.73,0,0,0,.728-.729V23.173a.729.729,0,0,0-.728-.728Z"/>
                <path d="M27.3,15.055a7.4,7.4,0,1,1,7.455-7.4A7.437,7.437,0,0,1,27.3,15.055Zm0-12.3a4.9,4.9,0,1,0,4.955,4.9A4.935,4.935,0,0,0,27.3,2.75Z"/>
            </svg>
        
        </a>
    </div>
    <div>
        <a href="{{ route('profile') }}">
            <svg fill="#000000" width="30px" height="30px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1,1,0,0,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1A10,10,0,0,0,15.71,12.71ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"/>
            </svg>
        </a>
    </div>
    <div>
        <a href="{{ !empty($user) ? route('allproducts')  : route('noLoginProduct')}}">
            <span style="margin-right: 0.5rem;"> <i class="fas fa-store element-pulse text-primary fa-2x" ></i></span>
        </a>
    </div>
    <div class="d-none">
        <select class="form-select form-select-sm py-0 px-1" style="max-width: 80px;"
                onchange="$(location).attr('href', '{{ route('language.switch', '') }}/' + $(this).val());">
            <option value="en" {{ Session('active_language') == 'en' ? 'selected' : '' }}>EN</option>
            <option value="uz" {{ Session('active_language') == 'uz' ? 'selected' : '' }}>UZ</option>
        </select>
    </div>
    <div>
        <a href="{{ route('allproducts') }}">
            <img src="{{asset('/assets/frontend/images/support.png')}}" width="30" alt="homiylik">
        </a>
    </div>
</div>

<style>
    
    
    .mobile-navigation {
        position: fixed;
        bottom: 10px;
        left: 10px;
        border-radius: 12px;
        right: 10px;
        padding: 10px 30px;
        box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 100;
        background-color: white;
    }
    
    main {
        padding-bottom: 80px;
    }
    .row.rightSideBarToggler {
        display:none;
    }
    @media only screen and (min-width: 993px) {
        .mobile-navigation {
            display: none;
        }
        main {
            padding-bottom: 0px;
        }
        .row.rightSideBarToggler {
            display:block;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let navbar = document.querySelector(".mobile-navigation");
        let lastElement = document.querySelector(".last-element"); // Oxirgi element
        
        function updateNavbarPosition() {
            // Check if lastElement exists
            if (lastElement) {
                let lastElementRect = lastElement.getBoundingClientRect();
                let windowHeight = window.innerHeight;
                
                if (lastElementRect.bottom <= windowHeight) {
                    navbar.style.position = "absolute";
                    navbar.style.bottom = (windowHeight - lastElementRect.bottom + 10) + "px";
                } else {
                    navbar.style.position = "fixed";
                    navbar.style.bottom = "10px";
                }
            } else {
                // If lastElement doesn't exist, just keep the navbar fixed at the bottom
                navbar.style.position = "fixed";
                navbar.style.bottom = "10px";
            }
        }
        
        window.addEventListener("scroll", updateNavbarPosition);
        updateNavbarPosition();
    });
</script>
