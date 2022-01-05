document.addEventListener("turbo:load", () => {
    // Костыль для открытия модального окна, если у него data-modal-open=1
    $('body').find('.modal[data-modal-open=1]').modal('show');
})
