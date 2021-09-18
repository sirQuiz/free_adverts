;(function ($) {
    let buttonToShowEditForm   = $('.js-single-advert-edit');
    let formToEdit             = $('.js-edit-form');
    let editFormClass          = "edit-form--shown";

    buttonToShowEditForm.click(function () {
       formToEdit.toggleClass(editFormClass);
    });
})(jQuery);