export function goToController() {

    document.getElementById("sel_selector").addEventListener("change", function () {
        this.form.submit();
    });

    document.removeEventListener("DOMContentLoaded", goToController);
}