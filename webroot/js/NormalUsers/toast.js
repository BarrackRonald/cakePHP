function toast({
    title = '', 
    message = '', 
    type = 'info', 
    duration = 3000}){
        const main = document.getElementById('toast');
        if(main){
            const icons = {
                success: 'fas fa-check-circle',
                info: 'fas fa-info-circle',
                warning: 'fas fa-exclamation-circle',
                error: 'fas fa-exclamation-circle',
            };
            const icon = icons[type];
            const delay = (duration / 1000).toFixed(2);
            const timeRemove = duration + 1000;
            const toast = document.createElement('div');

            // Auto remove toast
            const autoRemove =  setTimeout(function () {
                main.removeChild(toast)
                
            }, timeRemove);

            // Remove by button close
            toast.onclick = function (event) {
                if(event.target.closest('.toast__close')) {
                    main.removeChild(toast);
                    clearTimeout(autoRemove);
                    
                }
            }
            toast.style.animation = `showToast linear 0.3s, hidenToast linear 1s ${delay}s forwards `;
            toast.classList.add('toast', `toast--${type}`);
            toast.innerHTML = `
                <div class="toast__icon">
                    <i class="${icon}"></i>
                </div>

                <div class="toast__body">
                    <h3 class="toast__title">${title}</h3>
                    <p class="toast__message">${message}</p>
                </div>

                <div class="toast__close">
                    <i class="fas fa-times"></i>
                </div>
            
            `;
            main.appendChild(toast);
            

        }


}

// Success
function showSuccessToast(){
    toast({
        title: 'Thành Công!!!',
        message: 'Sản phẩm đã được thêm vào Giỏ hàng.',
        type: 'success',
        duration: 1000
    });
    
    
}
// Warning
function showWarningToast(){
    toast({
        title: 'Warning',
        message: 'Đang chờ kiểm tra lại.',
        type: 'warning',
        duration: 3000
    });
    
}
// Info
function showInfoToast(){
    toast({
        title: 'Info',
        message: 'Bạn nên kiểm tra lại thông tin.',
        type: 'info',
        duration: 3000
    });
}

// Error
function showErrorToast(){
    toast({
        title: 'Error',
        message: 'Bạn chưa đăng ký thành công tài khoản F8.',
        type: 'error',
        duration: 1000
    });
}

