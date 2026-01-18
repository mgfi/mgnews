document.addEventListener("DOMContentLoaded", () => {
    // 👁 TOGGLE EYE
    document.querySelectorAll(".password-toggle").forEach((btn) => {
        btn.addEventListener("click", () => {
            const input = document.getElementById(btn.dataset.target);
            const visible = input.type === "password";

            input.type = visible ? "text" : "password";
            btn.classList.toggle("active", visible);
            btn.innerHTML = visible ? window.eyeOn : window.eyeOff;
        });
    });

    // ✔ PASSWORD RULES
    const password = document.getElementById("password");

    const rules = {
        length: document.getElementById("rule-length"),
        number: document.getElementById("rule-number"),
        special: document.getElementById("rule-special"),
        uppercase: document.getElementById("rule-uppercase"),
        blacklist: document.getElementById("rule-blacklist"),
    };

    password.addEventListener("input", () => {
        const v = password.value;

        toggle(rules.length, v.length >= 8);
        toggle(rules.number, /\d/.test(v));
        toggle(rules.uppercase, /[A-Z]/.test(v));
        toggle(rules.blacklist, v.toLowerCase() !== "admin1234");

        // TYLKO PRAWDZIWE ZNAKI SPECJALNE
        toggle(rules.special, /[!@#$%^&*()_+\-=\[\]{};:'"\\|,.<>\/?~]/.test(v));
    });

    function toggle(el, ok) {
        el.classList.toggle("text-success", ok);
        el.classList.toggle("text-danger", !ok);
        el.innerHTML = (ok ? "✔ " : "❌ ") + el.innerText.slice(2);
    }
});
