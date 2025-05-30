<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome to BizzGrow</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            /* Allow vertical scrolling if content is too tall, especially on smaller screens */
            overflow-y: auto;
            overflow-x: hidden; /* Hide horizontal scrollbar, handled by JS/CSS snap */
            color: white;
            background: linear-gradient(to bottom right, #1E40AF, #0F172A); /* Background gradient */
        }

        .onboarding-main-wrapper {
            flex-grow: 1; /* Allow this wrapper to take available space */
            display: flex;
            flex-direction: column;
            justify-content: center; /* Center content vertically */
            align-items: center; /* Center content horizontally */
            width: 100%;
            padding: 20px; /* Overall padding for the main content area */
            box-sizing: border-box;
        }

        /* --- Slider Container & Behavior --- */
        .onboarding-slides-wrapper {
            display: flex;
            width: 100%;
            max-width: 900px; /* Max width for the entire slider area */
            height: auto; /* Adjust height based on content */
            /* Removed overflow-x: scroll and scroll-snap-type from here, will use JS for precise control after drag */
            /* Using transform for sliding */
            transition: transform 0.3s ease-out; /* Smooth transition for page changes */
            cursor: grab; /* Cursor for dragging */
        }

        .onboarding-slides-wrapper.is-dragging {
            cursor: grabbing;
        }

        .onboarding-slide {
            flex-shrink: 0; /* Prevent slides from shrinking */
            width: 100%; /* Each slide takes full width of the wrapper */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
            min-height: calc(100vh - 220px); /* Adjusted min-height to ensure space for controls */
            /* Allow vertical scrolling for content inside a slide if it's too tall */
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .onboarding-slide img {
            max-width: 70%;
            height: 300px;
            object-fit: contain;
            margin-bottom: 30px;
        }

        .onboarding-slide h1 {
            font-size: 38px;
            font-weight: 800; /* InterExtraBold */
            margin-bottom: 15px;
            line-height: 1.2;
            max-width: 600px;
        }

        .onboarding-slide p {
            font-size: 20px;
            font-weight: 400; /* InterRegular */
            line-height: 1.6;
            max-width: 700px;
            margin-bottom: 0;
        }

        /* --- Navigation Controls --- */
        .navigation-controls {
            padding: 20px 16px 40px;
            text-align: center;
            width: 100%;
            max-width: 900px; /* Align with slider max-width */
            box-sizing: border-box;
        }

        .indicator-dots {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            gap: 8px;
        }

        .indicator-dot {
            width: 10px;
            height: 10px;
            background-color: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            transition: width 0.2s ease-in-out, background-color 0.2s ease-in-out, border-radius 0.2s ease-in-out;
            cursor: pointer; /* Indicate clickable */
        }

        .indicator-dot.active {
            width: 25px;
            background-color: white;
            border-radius: 5px;
        }

        .button-group {
            display: flex;
            flex-direction: row;
            gap: 20px;
            max-width: 500px;
            margin: 0 auto;
            justify-content: center;
        }

        .btn {
            flex: 1;
            padding: 15px 25px;
            border: none;
            border-radius: 8px;
            font-size: 17px;
            font-weight: 700; /* InterBold for buttons */
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease, transform 0.2s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background-color: white;
            color: #1E40AF;
        }

        .btn-primary:hover {
            background-color: #f0f0f0;
        }

        .btn-secondary {
            background-color: transparent;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .btn-secondary.hidden {
            display: none;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .onboarding-slide h1 {
                font-size: 32px;
            }
            .onboarding-slide p {
                font-size: 18px;
            }
            .onboarding-slide img {
                height: 250px;
                margin-bottom: 25px;
            }
            .navigation-controls {
                padding: 15px 16px 30px;
            }
            .indicator-dot.active {
                width: 20px;
            }
            .button-group {
                flex-direction: column;
                gap: 10px;
                max-width: 300px;
            }
            .btn {
                padding: 13.5px 20px;
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .onboarding-slide h1 {
                font-size: 26px;
            }
            .onboarding-slide p {
                font-size: 16px;
            }
            .onboarding-slide img {
                height: 200px;
                margin-bottom: 20px;
            }
            .onboarding-slide {
                min-height: calc(100vh - 180px); /* Adjust min-height for smaller screens */
            }
            .navigation-controls {
                padding: 10px 10px 20px;
            }
        }
    </style>
</head>

<body>

    <div class="onboarding-main-wrapper">
        <div class="onboarding-slides-wrapper" id="onboarding-slides-wrapper">
            </div>

        <div class="navigation-controls">
            <div class="indicator-dots" id="indicator-dots">
                </div>

            <div class="button-group">
                <a href="#" id="nextButton" class="btn btn-primary"></a>
                <a href="#" id="skipButton" class="btn btn-secondary"></a>
            </div>
        </div>
    </div>

    <script>
        const onboardingData = [
            {
                "image": "{{ asset('images/onboarding1.png') }}",
                "title": "Atur Keuangan Bisnismu Lebih Mudah",
                "subtitle": "BizzGrow membantu UMKM mencatat pemasukan dan pengeluaran secara otomatis dan rapi, cukup lewat satu aplikasi.",
            },
            {
                "image": "{{ asset('images/onboarding2.png') }}",
                "title": "Didukung Kecerdasan Buatan (AI)",
                "subtitle": "BizzGrow memanfaatkan AI untuk menganalisis keuangan dan memberikan rekomendasi cerdas agar UMKM bisa ambil keputusan lebih tepat dan efisien."
            },
            {
                "image": "{{ asset('images/onboarding3.png') }}",
                "title": "Tumbuh Bersama BizzGrow",
                "subtitle": "Pantau pertumbuhan bisnismu lewat laporan keuangan yang mudah dipahami dan tampilan visual yang informatif. Semua dalam satu aplikasi.",
            },
        ];

        const slidesWrapper = document.getElementById('onboarding-slides-wrapper');
        const indicatorDotsContainer = document.getElementById('indicator-dots');
        const nextButton = document.getElementById('nextButton');
        const skipButton = document.getElementById('skipButton');

        let currentPage = 0;
        let isDragging = false;
        let startX = 0;
        let currentX = 0;
        let lastTranslate = 0; // Stores the translation value of the last snapped position
        let animationID;

        function setSliderPosition(translate) {
            slidesWrapper.style.transform = `translateX(${translate}px)`;
        }

        function getClientX(event) {
            return event.type.includes('mouse') ? event.pageX : event.touches[0].clientX;
        }

        function setTransition(enable) {
            slidesWrapper.style.transition = enable ? 'transform 0.3s ease-out' : 'none';
        }

        // --- Dragging Logic ---
        function dragStart(e) {
            isDragging = true;
            startX = getClientX(e);
            setTransition(false); // Disable transition during drag
            slidesWrapper.classList.add('is-dragging');
            
            // To prevent unwanted vertical scrolling on mobile when dragging horizontally
            if (e.type.includes('touch')) {
                e.preventDefault(); 
            }
        }

        function drag(e) {
            if (!isDragging) return;
            currentX = getClientX(e);
            let deltaX = currentX - startX;
            setSliderPosition(lastTranslate + deltaX);
        }

        function dragEnd() {
            if (!isDragging) return;
            isDragging = false;
            slidesWrapper.classList.remove('is-dragging');
            setTransition(true); // Re-enable transition for snapping

            let movedBy = getClientX(event) - startX;
            const slideWidth = slidesWrapper.clientWidth;

            if (movedBy < -50 && currentPage < onboardingData.length - 1) { // Swiped left enough
                currentPage++;
            } else if (movedBy > 50 && currentPage > 0) { // Swiped right enough
                currentPage--;
            }

            snapToPage();
            updateUI(); // Update UI after snapping
        }

        function snapToPage() {
            const slideWidth = slidesWrapper.clientWidth;
            lastTranslate = -currentPage * slideWidth;
            setSliderPosition(lastTranslate);
        }

        // --- Event Listeners for Dragging ---
        slidesWrapper.addEventListener('mousedown', dragStart);
        slidesWrapper.addEventListener('mousemove', drag);
        slidesWrapper.addEventListener('mouseup', dragEnd);
        slidesWrapper.addEventListener('mouseleave', () => { // End drag if mouse leaves wrapper
            if (isDragging) dragEnd();
        });

        slidesWrapper.addEventListener('touchstart', dragStart, { passive: false }); // passive: false to allow e.preventDefault()
        slidesWrapper.addEventListener('touchmove', drag, { passive: false });
        slidesWrapper.addEventListener('touchend', dragEnd);

        // Prevent dragging images
        slidesWrapper.addEventListener('dragstart', (e) => {
            e.preventDefault();
        });


        // --- UI Rendering & Logic ---
        function renderSlides() {
            slidesWrapper.innerHTML = '';
            onboardingData.forEach((data, index) => {
                const slide = document.createElement('div');
                slide.className = 'onboarding-slide';
                slide.innerHTML = `
                    <img src="${data.image}" alt="Onboarding Image ${index + 1}">
                    <h1>${data.title}</h1>
                    <p>${data.subtitle.replace(/\n/g, '<br>')}</p>
                `;
                slidesWrapper.appendChild(slide);
            });
            snapToPage(); // Set initial position
            updateUI(); // Initial UI update
        }

        function renderIndicatorDots() {
            indicatorDotsContainer.innerHTML = '';
            onboardingData.forEach((_, index) => {
                const dot = document.createElement('div');
                dot.className = 'indicator-dot';
                if (index === currentPage) {
                    dot.classList.add('active');
                }
                dot.addEventListener('click', () => {
                    currentPage = index;
                    snapToPage(); // Snap to clicked page
                    updateUI(); // Update UI after snapping
                });
                indicatorDotsContainer.appendChild(dot);
            });
        }

        function updateUI() {
            // Update button text and visibility
            if (currentPage === onboardingData.length - 1) {
                nextButton.textContent = 'Mulai BizzGrow';
                skipButton.classList.add('hidden'); // Hide skip button on last slide
            } else {
                nextButton.textContent = 'Next';
                skipButton.textContent = 'Skip';
                skipButton.classList.remove('hidden'); // Ensure skip button is visible
            }

            // Update button links
            if (currentPage === onboardingData.length - 1) {
                nextButton.href = "{{ route('welcome') }}"; // Link to welcome page
            } else {
                nextButton.href = "#"; // No specific link, handled by JS for internal slide change
            }
            skipButton.href = "{{ route('welcome') }}"; // Skip always goes to welcome

            // Update indicator dots
            renderIndicatorDots();
        }

        function goToNextPage() {
            if (currentPage < onboardingData.length - 1) {
                currentPage++;
                snapToPage(); // Snap to next page
                updateUI();
            } else {
                // Last page, navigate to welcome. The link is handled by nextButton.href
                window.location.href = nextButton.href;
            }
        }

        // Event Listeners for buttons
        nextButton.addEventListener('click', (e) => {
            if (nextButton.href === "#" || nextButton.href.endsWith('#')) { // Only prevent default if it's an internal hash link
                e.preventDefault();
                goToNextPage();
            }
            // Otherwise, let the default link behavior take over (redirect to welcome)
        });

        // Skip button directly navigates to welcome page
        skipButton.addEventListener('click', (e) => {
            // No need to preventDefault here, let the link do its job
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight') {
                goToNextPage();
            } else if (e.key === 'ArrowLeft') {
                if (currentPage > 0) {
                    currentPage--;
                    snapToPage(); // Snap to previous page
                    updateUI();
                }
            }
        });

        // Initial render when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            renderSlides();
            // Initial positioning for resize
            window.addEventListener('resize', () => {
                snapToPage(); // Recalculate and snap to current page on resize
                updateUI();
            });
        });
    </script>

</body>

</html>