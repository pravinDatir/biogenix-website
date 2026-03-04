document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("contactForm");
    if (!form) return;

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const fields = [
            { id: "firstName", rules: ["required"] },
            { id: "lastName", rules: ["required"] },
            { id: "email", rules: ["required", "email"] },
            { id: "description", rules: ["required"] }
        ];

        const isValid = validateFields(fields);

        if (!isValid) return false;

        const formStatus = document.getElementById("formStatus");
        formStatus.textContent = "Query submitted successfully";
        formStatus.classList.add("success");

        console.log("Form submitted:", {
            firstName: firstName.value,
            lastName: lastName.value,
            email: email.value,
            description: description.value
        });

        form.reset();
        return false;
    });

});