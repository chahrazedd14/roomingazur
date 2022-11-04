getEstablishment();

function getEstablishment() {
    let formData = new FormData();
    formData.append("type", "getEstablishment");
    let http = new Http("./adminapi", "POST", formData);
    http.onSuccessCallback((data) => {
        let json = JSON.parse(data);
        if (json.status === "success") {
            for (let obj of json.data) {
                let establishment = document.getElementById("establishment");
                establishment.insertAdjacentHTML("beforeend", "<option value='" + obj.id + "'>" + obj.name + "</option>")
            }
        }
    })
    http.start();
}
let booking = document.getElementById("booking");
let submit = document.getElementById("submit");
let msg = document.createElement("div");
let msgP = document.createElement("p");
msg.classList.add("msg");
msg.insertAdjacentElement("beforeend", msgP);
submit.addEventListener("click", (e) => {
    e.preventDefault();
    if (submit.classList.contains("disabled")) {
        return;
    }
    let clientName = document.getElementById("client-name");
    let bookingId = document.getElementById("booking-id");
    let date = document.getElementById("date");
    let establishment = document.getElementById("establishment");
    let clientEmail = document.getElementById("client-email");
    let file = document.getElementById("file");

    if (clientName.value === "" || clientEmail.value === "" ||
        bookingId.value === "" || date === "" || establishment.value === "" ||
        state.files.length === 0) {

        let fields = [];
        if (clientName.value === "") {
            fields.push("Name");
        }
        if (clientEmail.value === "") {
            fields.push("Email");
        }
        if (bookingId.value === "") {
            fields.push("Booking ID");
        }
        if (date.value === "") {
            fields.push("Date");
        }
        if (establishment.value === "") {
            fields.push("Establishment");
        }
        if (state.files.length === 0) {
            fields.push("File");
        }

        if (fields.length == 1){
            msgP.innerText = fields.join(", ") + " field required";
        }else{
            msgP.innerText = fields.join(", ") + " fields required";
        }
        booking.appendChild(msg);
        setTimeout(() => {
            msg.classList.add("open");
        }, 20);
        setTimeout(() => {
            msg.classList.remove("open");
        }, 2000);
        setTimeout(() => {
            msg.remove();
        }, 2100);
        return;
    }

    let formData = new FormData();
    formData.append("type", "add");
    formData.append("clientName", clientName.value);
    formData.append("bookingId", bookingId.value);
    formData.append("date", date.value);
    formData.append("establishment", establishment.children[establishment.selectedIndex].innerText);
    formData.append("etab_id", establishment.value);
    formData.append("clientEmail", clientEmail.value);
    for (let i in state.files) {
        formData.append("files[]", state.files[i]);
    }
    let loader = document.createElement("div");
    loader.classList.add("loader");
    let http = new Http("./adminapi", "POST", formData);
    http.onSuccessCallback((data) => {
        submit.classList.remove("disabled");
        loader.remove();
        let json = JSON.parse(data);
        if (json.status === "success") {
            clientEmail.value = null;
            establishment.value = null;
            clientName.value = null;
            bookingId.value = null;
            date.value = null;
            actions.resetState();
        } else {
        }
        msgP.innerText = json.message;
        booking.appendChild(msg);
        setTimeout(() => {
            msg.classList.add("open");
        }, 20);
        setTimeout(() => {
            msg.classList.remove("open");
        }, 2000);
        setTimeout(() => {
            msg.remove();
        }, 2100);
    })
    http.start();
    submit.classList.add("disabled");
    submit.insertAdjacentElement("beforeend", loader);
});


function email_subscribepopup(e) {
    let subscribe_pemail = document.getElementById("subscribe_pemail");
    let formData = new FormData();
    formData.append("type", "delete");
    formData.append("id", subscribe_pemail.value);
    let http = new Http("./adminapi", "POST", formData);
    http.onSuccessCallback((data) => {
        let json = JSON.parse(data);
        let booking = document.getElementById("booking");
        if (json.status === "success") {
            msgP.innerText = "Booking ID " + subscribe_pemail.value + " stopped";
            document.getElementsByClassName("close")[0].click();
        } else {
            msgP.innerText = "Something went wrong";
        }
        booking.appendChild(msg);
        setTimeout(() => {
            msg.classList.add("open");
        }, 20);
        setTimeout(() => {
            msg.classList.remove("open");
        }, 2000);
        setTimeout(() => {
            msg.remove();
        }, 2100);

    });
    http.start();

}