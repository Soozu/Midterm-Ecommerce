document.addEventListener('DOMContentLoaded', function () {
    var loginModal = document.getElementById('loginModal');
    var registerModal = document.getElementById('registerModal');
    var loginRegisterLink = document.getElementById('loginRegisterLink');
    var switchToRegister = document.getElementById('switchToRegister');
    var switchToLogin = document.getElementById('switchToLogin');
    var closeSpans = document.getElementsByClassName('close');
  
    if (typeof loginError !== 'undefined' && loginError) {
        loginModal.style.display = 'block';
    }
  
    if (loginRegisterLink) {
        loginRegisterLink.onclick = function(event) {
            event.preventDefault();
            loginModal.style.display = 'block';
        };
    }

    if (switchToRegister) {
        switchToRegister.onclick = function(event) {
            event.preventDefault();
            loginModal.style.display = 'none';
            registerModal.style.display = 'block';
        };
    }

    if (switchToLogin) {
        switchToLogin.onclick = function(event) {
            event.preventDefault();
            registerModal.style.display = 'none';
            loginModal.style.display = 'block';
        };
    }

    Array.from(closeSpans).forEach(span => {
        span.onclick = function() {
            this.parentElement.parentElement.style.display = 'none';
        };
    });

    window.onclick = function(event) {
        if (event.target === loginModal || event.target === registerModal) {
            event.target.style.display = 'none';
        }
    };
});
