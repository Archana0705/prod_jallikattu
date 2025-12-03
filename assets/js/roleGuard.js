
// Define role-to-folder mapping
const rolePageMap = {
    'Event Organizer': 'event-organizer',
    'Magesterial huzur sharishthadhar MHS': 'magesterial-huzur',
    'District Collector': 'district-collector',
    'Assistant': 'assistant',
    'Superindentent /Manager': 'superindentent-manager',
    'Veterinary Assistant Surgeon (J Section)': 'veterinary-assistant-surgeon',
    'Assistant Director ( J Section)': 'assistant-director',
    'Joint Director (Monitoring Cell)': 'joint-director',
    'Additional Director': 'additional-director',
    'Director of Animal Husbandry & Veterinary Services': 'director-of-animal-husbandry-veterinary-services',
    'Principal Secretary/Additional Chief Secretary': 'principal-secretary',
    'Participants': 'participant-register',
    'Bull Registration': 'bull-register'
};

const secretKey = 'V7gN4dY8pT2xB3kRz';
const encrypted = localStorage.getItem("userDetails");
function showSuccessToast(message) {
    const toastElement = document.getElementById('successToast');
    const toastBody = document.getElementById('successMessage');

    // Set the dynamic message
    toastBody.textContent = message;

    // Initialize and show the toast
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 3000
    });
    toast.show();
}
// Function to show a error toast with a dynamic message
function showErrorToast(message) {
    const toastElement = document.getElementById('errorToast');
    const toastBody = document.getElementById('errorMessage');

    // Set the dynamic message
    toastBody.textContent = message;

    // Initialize and show the toast
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 3000
    });
    toast.show();
}

if (!encrypted) {
    showErrorToast("Session expired or not logged in.");
    window.location.href = "../index.html";
} else {
    try {
        const decrypted = CryptoJS.AES.decrypt(encrypted, secretKey).toString(CryptoJS.enc.Utf8);
        const user = JSON.parse(decrypted);
        const role = user.role;
        const expectedPath = rolePageMap[role];

        const currentFolder = window.location.pathname.toLowerCase();

        if (!expectedPath || !currentFolder.includes(expectedPath)) {
            window.location.href = "../index.html";
        }
    } catch (e) {
        console.error("Decryption error", e);
        localStorage.removeItem("userDetails");
        window.location.href = "../index.html";
    }
}
