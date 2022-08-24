export const Notify = {
    success(message) {
        window.UIkit.notification({
            message: message,
            status: 'success',
            pos: 'bottom-center',
            timeout: 3000
        });
    },
    warning(message) {
        window.UIkit.notification({
            message: message,
            status: 'warning',
            pos: 'bottom-center',
            timeout: 3000
        });
    },
    fail(message) {
        window.UIkit.notification({
            message: message,
            status: 'danger',
            pos: 'bottom-center',
            timeout: 3000
        });
    },
};
