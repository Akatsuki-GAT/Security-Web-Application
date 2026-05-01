function sendMail(){
    let parms = {
        username: document.getElementById("username").value,
        contact: document.getElementById("contact-info").value,
        message: document.getElementById("contact-message").value,
    }
    console.log("PARMS:", parms);
    emailjs.send("service_8v6gq1m","template_ijsqpzo",parms).then(alert("Contact Form sent!!!"));
}