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

// Function to select an option
function selectOption(element, stepId, inputName, inputValue) {
    // Remove 'selected' class from all options in this step
    document.querySelectorAll(`#${stepId} .option`).forEach(option => {
        option.classList.remove('selected');
    });

    // Add 'selected' class to clicked option
    element.classList.add('selected');

    // Set value
    document.querySelector(`[name="${inputName}"]`).value = inputValue;
}

// Function to select multiple options (checkbox behavior)
function toggleOption(element, stepId, inputName,) {
    // Toggle 'selected' class on clicked option
    element.classList.toggle('selected');

    // Get all selected options inside this step
    const selectedOptions = Array.from(document.querySelectorAll(`#${stepId} .option.selected`));

    const selectedValues = selectedOptions.map(option => option.getAttribute('data-value'));

    document.querySelector(`[name="${inputName}"]`).value = selectedValues.join(',');
}

// All step required validation
document.addEventListener('DOMContentLoaded', function () {
    const steps = document.querySelectorAll('.step');

    steps.forEach(step => {
        if (step.classList.contains('step-unique')) {
            return;
        }

        const nextBtn = step.querySelector('.next-btn');
        if (!nextBtn) return;

        const inputs = step.querySelectorAll('input, select, textarea, .option');

        inputs.forEach(input => {
            input.addEventListener('input', checkInputs);
            input.addEventListener('click', checkInputs);
        });

        function checkInputs() {
            const allFilled = Array.from(inputs).every(i => {
                // console.log('i', i)
                if (i.matches('.option')) {
                    return step.querySelector('.option.selected') !== null;
                }
                return i.value.trim() !== '';
            });
            // console.log('allFilled', allFilled)

            nextBtn.disabled = !allFilled;
        }
    });
});

function validateFloor() {
    let floor = document.getElementById('floor').value.trim();
    let errorMsg = document.getElementById("floor_error");

    if (!floor) {
        // errorMsg.innerHTML = 'Please enter a floor number';
        return false;
    }

    if (isNaN(floor)) {
        errorMsg.innerHTML = 'Only numeric values are allowed.';
        return false;
    }

    if (parseInt(floor) >= 50) {
        errorMsg.innerHTML = 'Please enter a floor number less than 50.';
        return false;
    }

    errorMsg.innerHTML = '';
    return true;
}

function validateUnit() {
    let unit = document.getElementById("unit").value.trim();
    let errorMsg = document.getElementById("unit_error");

    if (!unit) {
        // errorMsg.innerHTML = 'Please enter an unit';
        return false;
    }

    if (!/^\d{2,4}$/.test(unit)) {
        errorMsg.innerHTML = 'Please enter a number between 2 and 4 digits.';
        return false;
    }

    errorMsg.innerHTML = '';
    return true;
}

function checkStepValidation(step) {
    let nextButton;

    if (step == 'floorUnitStep') {
        nextButton = document.getElementById("floorUnitStep");

        if (validateFloor() && validateUnit()) {
            nextButton.disabled = false;
            return;
        } else {
            nextButton.disabled = true;
            return;
        }
    }
    
    if (step == 'postalCodeStep') {
        nextButton = document.getElementById("postalCodeStep");

        // if (validateFloor() && validateUnit()) {
        //     nextButton.disabled = true;
        // } else {
        //     nextButton.disabled = false;
        // }
    }
    return;
}

function validatePhone() {
    let phoneInput = document.getElementById("input_phone");
    let errorMsg = document.getElementById("phone_error");

    phoneInput.value = phoneInput.value.replace(/\D/g, '');

    let phonePattern = /^[89]\d{7}$/; // Starts with 8 or 9 and has 8 digits
    if (!phonePattern.test(phoneInput.value)) {
        errorMsg.innerHTML = 'Phone number should start with 9 or 8 and must be 8 digits long.';
        return false;
    }

    errorMsg.innerHTML = '';
    return true;
}

function validateEmail() {
    let emailInput = document.getElementById("input_email");
    let errorMsg = document.getElementById("email_error");

    // Email validation pattern
    let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(emailInput.value)) {
        errorMsg.innerHTML = 'Please enter a valid email address.';
        return false;
    }
    
    errorMsg.innerHTML = '';
    return true;
}

function checkOtpButton() {
    const otpButtons = document.getElementsByClassName("otp-button");

    if (validatePhone() && validateEmail()) {
        for (let btn of otpButtons) {
            btn.disabled = false;
        }
    } else {
        for (let btn of otpButtons) {
            btn.disabled = true;
        }
    }
}

function validateOtp() {
    let input = document.getElementById("input_otp");
    let errorMsg = document.getElementById("otp_error");

    if (!input) {
        errorMsg.innerHTML = 'Please enter the OTP.';
        return false;
    }

    errorMsg.innerHTML = "";
    return true;
}

function checkSubmitButton() {
    const submitBtn = document.getElementById("submitBtn");

    if (!validateOtp()) {
        submitBtn.disabled = true;
        return;
    }
    
    submitBtn.disabled = false;
    return;
}

let isSendingOtp = false;
let isResendingOtp = false;
let resendCountdown = 120;
let resendInterval;

function startResendTimer() {
    const resendBtn = document.getElementById("resendOtpBtn");
    const resendTimer = document.getElementById("resendTimer");

    resendBtn.disabled = true;
    resendCountdown = 5;
    resendTimer.textContent = `02:00`;

    resendInterval = setInterval(() => {
        resendCountdown--;
        const minutes = Math.floor(resendCountdown / 60);
        const seconds = resendCountdown % 60;
        resendTimer.textContent = `${minutes < 10 ? '0' + minutes : minutes}:${seconds < 10 ? '0' + seconds : seconds}`;

        if (resendCountdown <= 0) {
            clearInterval(resendInterval);
            resendBtn.disabled = false;
            resendTimer.textContent = "";
        }
    }, 1000);
}

async function sendOtp() {
    if (isSendingOtp) return;
    isSendingOtp = true;

    const form = document.getElementById("leadForm");
    const formData = new FormData(form);
    const urlEncodedData = new URLSearchParams(formData).toString();
  
    try {
        const response = await fetch('../otp_send.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: urlEncodedData
        });

        const data = await response.json();
        // console.log('data', data);
        if (data.success) {
            // alert('Lead saved and OTP sent successfully!');
            sessionStorage.setItem('lead_id', data.lead_id);

            const otpContainer = document.getElementById("otpContainer");
            otpContainer.style.display = 'block';
            const sendOtpBtn = document.getElementById("sendOtpBtn");
            sendOtpBtn.style.display = 'none';
            const resendOtpBtn = document.getElementById("resendOtpBtn");
            resendOtpBtn.style.display = 'block';
            startResendTimer();
        } else {
            alert('Failed to process request: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while processing the request.');
    }
}

async function resendOtp() {
    if (isResendingOtp) return;
    isResendingOtp = true;

    const leadId = sessionStorage.getItem('lead_id');
    if (!leadId) {
        alert("Lead ID is missing!");
        isResendingOtp = false;
        return;
    }

    try {
        const response = await fetch('../otp_resend.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `lead_id=${encodeURIComponent(leadId)}`
        });

        const data = await response.json();
        // console.log('data', data);
        if (data.success) {
            const resendOtpBtn = document.getElementById("resendOtpBtn");
            resendOtpBtn.disabled = true;

            // alert('OTP has been resent!');
            startResendTimer();
        } else {
            alert('Failed to resend OTP: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while resending OTP.');
    } finally {
        isSendingOtp = false;
    }
}

async function verifyOtp(event) {
    event.preventDefault();

    const otpInput = document.getElementById("input_otp");
    const otpError = document.getElementById("otp_error");
    const otp = otpInput.value.trim();

    const leadId = sessionStorage.getItem('lead_id');
    if (!leadId) {
        alert("Lead ID is missing!");
        isResendingOtp = false;
        return;
    }

    if (!otp) {
        otpError.style.display = "block";
        otpError.textContent = "Verify your code first!";
        return;
    }

    try {
        const response = await fetch('../otp_verify.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `lead_id=${encodeURIComponent(leadId)}&otp=${encodeURIComponent(otp)}`
        });

        const data = await response.json();
        // console.log('data', data);
        if (data.success) {
            otpError.style.display = "none";
            document.getElementById("leadForm").submit();
        } else {
            otpError.style.display = "block";
            otpError.textContent = data.message || "Invalid OTP. Please try again.";
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while verifying OTP. Please try again.');
    }
}
