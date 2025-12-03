
function loadPopup() {
  fetch('../assets/ChangePassword/ChangePassword.html')
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.text();
    })
    .then(html => {
      document.body.insertAdjacentHTML('beforeend', html);
    })
    .catch(error => {
      console.error('Error loading popup:', error);
    });
}
$(document).on('click', '.a-Menu-item', function () {
  if ($(this).is(':last-of-type')) {
    window.location.href = "../index.html";
  }
});
$(document).on('click', '.a-Menu-item', function () {
  if ($(this).is(':first-of-type')) {
    $('#popup-overlay').fadeIn();
    $('.t-Header').css('z-index', '0');
    $('#close-popup, #popup-overlay').click(function () {
      $('#popup-overlay').fadeOut();
      $('.t-Header').css('z-index', '800');
    });
    $('#changePasswordSubmit').on('click', async function () {
      const old_password = $('#Old_password_input').val();
      const new_password = $('#New_password_input').val();
      if (old_password === "" || new_password === "") {
        showErrorToast("Please fill all required fields");
      } else {
        const user_id = userId;
        const formData = new FormData();
        formData.append('employee_id', user_id);
        formData.append('new_password', new_password);
        formData.append('old_password', old_password);
        $.ajax({
          url: 'https://tngis.tnega.org/lcap_api/jallikattu/v1/access/change_password',
          type: 'POST',
          headers: {
            'X-App-Key': 'j6ll!k@ttu',
            'X-App-Name': 'tn jallikattu',
          },
          processData: false,
          contentType: false,
          data: formData,
          success: function (response) {
            console.log('Response:', response);
            showSuccessToast("Password Changed Successfully");
            setTimeout(function () {
              $('#popup-overlay').fadeOut();
              $('.t-Header').css('z-index', '800');
            }, 1000);
          },
          error: function (xhr, status, error) {
            console.error('Error:');
            showErrorToast('error occured');
          }
        });
      }
    })
    $('#popup-content').click(function (e) {
      e.stopPropagation();
    });
  }
});

$(document).ready(function () {
  //$('body').attr('onload', 'loadPopup()');
  $(window).on("pageshow", function (event) {
    if (window.location.href === "https://tngis.tnega.org/jallikattu/index.html") {
      if (event.originalEvent.persisted) {
        alert('loaded-from-cache');
        window.location.href = "../index.html";
      }
    }
  });
  let encryptedDataFromStorage = localStorage.getItem('userDetails');
  let decryptedData;
  if (encryptedDataFromStorage) {
    const secretKey = 'V7gN4dY8pT2xB3kRz';
    const bytes = CryptoJS.AES.decrypt(encryptedDataFromStorage, secretKey);
    decryptedData = bytes.toString(CryptoJS.enc.Utf8);
  } else {
    console.log('No data found in localStorage');
  }
  let userDetails = decryptedData;
  const user = JSON.parse(userDetails);
  let userId = user.user_id;
});
