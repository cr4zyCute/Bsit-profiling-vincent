const formOpenBtn = document.querySelector(".login-btn"), 
  formContainer = document.querySelector(".form_container"),
  formCloseBtn = document.querySelector(".form_close"),
  pwShowHide = document.querySelectorAll(".pw_hide");

formOpenBtn.addEventListener("click", () => formContainer.classList.add("active")); 
formCloseBtn.addEventListener("click", () => formContainer.classList.remove("active")); 

pwShowHide.forEach((icon) => {
  icon.addEventListener("click", () => {
    let getPwInput = icon.parentElement.querySelector("input");
    if (getPwInput.type === "password") {
      getPwInput.type = "text";
      icon.classList.replace("uil-eye-slash", "uil-eye");
    } else {
      getPwInput.type = "password";
      icon.classList.replace("uil-eye", "uil-eye-slash");
    }
  });
});

window.addEventListener("DOMContentLoaded", () => {
  const hasError = document.getElementById("hasError").value;
  if (hasError === "true") {
    formContainer.classList.add("active");
  }
});
