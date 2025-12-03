function validateSession(expectedRole) {
    const encryptedUserData = localStorage.getItem('userDetails');
    if (!encryptedUserData) {
        alert("Session expired or logged in from another device.");
        window.location.href = "../index.html";
    } else {
        try {
            const decrypted = CryptoJS.AES.decrypt(encryptedUserData, 'V7gN4dY8pT2xB3kRz').toString(CryptoJS.enc.Utf8);
            const user = JSON.parse(decrypted);

            const roleMap = {
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

            const userRolePath = roleMap[user.role];
            if (userRolePath !== expectedRole) {
                alert("Unauthorized access. Redirecting to login.");
                localStorage.clear();
                window.location.href = "../index.html";
            }
        } catch (e) {
            localStorage.clear();
            alert("Invalid session. Please login again.");
            window.location.href = "../index.html";
        }
    }
}
