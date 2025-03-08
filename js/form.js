// Cursor follower effect
function initCursorFollower() {
    const follower = document.querySelector('.cursor-follower');
    let mouseX = 0,
        mouseY = 0;
    let followerX = 0,
        followerY = 0;

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
        follower.style.opacity = '1';
    });

    function animate() {
        // Smooth following with easing
        followerX += (mouseX - followerX) * 0.1;
        followerY += (mouseY - followerY) * 0.1;

        if (follower) {
            follower.style.left = `${followerX}px`;
            follower.style.top = `${followerY}px`;
        }

        requestAnimationFrame(animate);
    }

    animate();

    // Hide follower when mouse leaves window
    document.addEventListener('mouseleave', () => {
        follower.style.opacity = '0';
    });

    document.addEventListener('mouseenter', () => {
        follower.style.opacity = '1';
    });
}

// Function to navigate to next step
function nextStep() {
    if (currentStep < totalSteps) {
        document.getElementById(`step${currentStep}`).classList.remove('active');
        currentStep++;
        document.getElementById(`step${currentStep}`).classList.add('active');

        // Update user info based on current step
        updateUserInfo();

        // Update progress bar
        updateProgressBar();
    }
}

// Function to navigate to previous step
function prevStep() {
    if (currentStep > 1) {
        document.getElementById(`step${currentStep}`).classList.remove('active');
        currentStep--;
        document.getElementById(`step${currentStep}`).classList.add('active');

        // Update user info based on current step
        updateUserInfo();

        // Update progress bar
        updateProgressBar();
    }
}

// Function to update progress bar
function updateProgressBar() {
    const progressBar = document.getElementById('progressBar');
    const progressPercentage = (currentStep / totalSteps) * 100;
    progressBar.style.width = `${progressPercentage}%`;
}

// Function to update user info based on current step
function updateUserInfo() {
    const userNameElement = document.getElementById('userName');

    if (currentStep === 1) {
        userNameElement.textContent = 'Muhammad Faris Bin Zulkifli';
    } else if (currentStep === 2) {
        userNameElement.textContent = 'Brandon Ng Jia Hao';
    } else if (currentStep === 3) {
        userNameElement.textContent = 'Brandon Ng Jia Hao';
    } else if (currentStep === 4) {
        userNameElement.textContent = 'Emily Tan Wei Ling';
    } else if (currentStep === 5) {
        userNameElement.textContent = 'Emily Tan Wei Ling';
    } else if (currentStep === 6) {
        userNameElement.textContent = 'Brandon Ng Jia Hao';
    } else if (currentStep === 7) {
        userNameElement.textContent = 'Muhammad Faris Bin Zulkifli';
    }
}

// Initialize effects and progress bar on page load
window.addEventListener('DOMContentLoaded', () => {
    initCursorFollower();
    updateProgressBar();
});

document.addEventListener('DOMContentLoaded', function() {
    const steps = document.querySelectorAll('.step');

    steps.forEach(step => {
        const nextBtn = step.querySelector('.next-btn');
        if (!nextBtn) return;

        const inputs = step.querySelectorAll('input, select, textarea, .option');

        inputs.forEach(input => {
            input.addEventListener('input', checkInputs);
            input.addEventListener('click', checkInputs);
        });

        function checkInputs() {
            const allFilled = Array.from(inputs).every(i => {
                if (i.matches('.option')) {
                    return step.querySelector('.option.selected') !== null;
                }
                return i.value.trim() !== '';
            });

            nextBtn.disabled = !allFilled;
        }
    });
});

function checkSubmit() {
    let name = document.getElementById('input_name').value.trim();
    let phone = document.getElementById('input_phone').value.trim();
    let email = document.getElementById('input_email').value.trim();
    document.getElementById('submitBtn').disabled = !(name && phone && email);
}

function validatePhone() {
    let phoneInput = document.getElementById("input_phone");
    let errorMsg = document.getElementById("phone_error");
    let submitBtn = document.getElementById("submitBtn");

    phoneInput.value = phoneInput.value.replace(/\D/g, '');

    let phonePattern = /^[89]\d{7}$/; // Starts with 8 or 9 and has 8 digits

    if (!phonePattern.test(phoneInput.value)) {
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
    } else {
        errorMsg.style.display = "none";
        submitBtn.disabled = false;
    }
}

function validateEmail() {
    let emailInput = document.getElementById("input_email");
    let errorMsg = document.getElementById("email_error");
    let submitBtn = document.getElementById("submitBtn");

    // Email validation pattern
    let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    if (!emailPattern.test(emailInput.value)) {
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
    } else {
        errorMsg.style.display = "none";
        submitBtn.disabled = false;
    }
}

document.getElementById("leadForm").addEventListener("submit", async function(event) {
    event.preventDefault();

    let formData = new FormData(this);

    try {
        let response = await fetch(this.action, {
            method: "POST",
            body: formData
        });

        let result = await response.json();

        if (result.status === "success") {
            window.location.href = "../report/index.html";
        } else {
            alert("Error: " + result.message);
        }
    } catch (error) {
        console.error("Request failed", error);
        alert("Something went wrong!");
    }
});