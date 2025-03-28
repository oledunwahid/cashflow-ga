// Toggle visibility password
Array.from(document.querySelectorAll("form .auth-pass-inputgroup")).forEach(function(s){
    Array.from(s.querySelectorAll(".password-addon")).forEach(function(t){
        t.addEventListener("click",function(t){
            var e=s.querySelector(".password-input");
            "password"===e.type?e.type="text":e.type="password";
        })
    });
});

// JavaScript untuk validasi
var password = document.getElementById('password-input'),
    confirm_password = document.getElementById('confirm-password-input'),
    message = document.getElementById('message'); // Tempat untuk menampilkan pesan validasi

// Fungsi validasi kecocokan password
function validatePassword(){
  if(password.value === confirm_password.value){
    confirm_password.setCustomValidity('');
    message.textContent = ''; // Kosongkan pesan jika password cocok
  } else {
    confirm_password.setCustomValidity("Password Don't Match");
    message.textContent = "Password Don't Match"; // Tampilkan pesan
  }
}

// Event listeners untuk validasi
password.addEventListener('input', validatePassword);
confirm_password.addEventListener('input', validatePassword);

// Memaksa validasi pada saat halaman dimuat agar pesan kesalahan muncul sejak awal jika form sudah terisi
window.onload = validatePassword;

// Definisi variabel untuk validasi criteria password
var myInput = document.getElementById("password-input"),
    letter = document.getElementById("pass-lower"),
    capital = document.getElementById("pass-upper"),
    number = document.getElementById("pass-number"),
    length = document.getElementById("pass-length");

// Event listeners untuk menampilkan kriteria password
myInput.onfocus = function(){document.getElementById("password-contain").style.display="block"};
myInput.onblur = function(){document.getElementById("password-contain").style.display="none"};

// Validasi kriteria password saat user mengetik
myInput.onkeyup = function(){
  // Validasi lowercase letters
  myInput.value.match(/[a-z]/g) ? (letter.classList.remove("invalid"), letter.classList.add("valid")) : (letter.classList.remove("valid"), letter.classList.add("invalid"));
  
  // Validasi uppercase letters
  myInput.value.match(/[A-Z]/g) ? (capital.classList.remove("invalid"), capital.classList.add("valid")) : (capital.classList.remove("valid"), capital.classList.add("invalid"));
  
  // Validasi numbers
  myInput.value.match(/[0-9]/g) ? (number.classList.remove("invalid"), number.classList.add("valid")) : (number.classList.remove("valid"), number.classList.add("invalid"));
  
  // Validasi length
  myInput.value.length >= 8 ? (length.classList.remove("invalid"), length.classList.add("valid")) : (length.classList.remove("valid"), length.classList.add("invalid"));
};
