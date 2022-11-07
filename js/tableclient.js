function filterTable() {
    $('#datatable').DataTable().draw(true);
}


$('#query_f').keypress(function (e) {
    if (e.which == 13) {
        filterTable();
    }
});

$('#date_f').keypress(function (e) {
    if (e.which == 13) {
        filterTable();
    }
});

$('#room_num_f').keypress(function (e) {
    if (e.which == 13) {
        filterTable();
    }
});


function editFun(e, id) {
    if (e.classList.contains("disabled")) {
        return;
    }
    let btn = $(e);
    let tr = btn.parent().parent();

    if (btn.hasClass('btn-primary')) {
        updateRow(e);

    } else {
        btn.addClass('btn-primary');
        btn.empty().append("<img class=\"fit-picture\" src=\"../medias/fleche-vers-le-haut-de-sauvegarde-dans-le-cloud.png\" alt=\"save-icons\">");
        let classes = ['', 'arrive-date', '', 'first-name', 'last-name', 'birthday', 'observation-client'];
        let tr = e.parentElement.parentElement;
        let td = tr.querySelectorAll("td");
        let valid = true;
        for (let i = 0; i < td.length; i++) {
            var content = $(td[i]).html();
            if (content == "" && td[i].querySelector(".observation-client") == null) {
                valid = false;
            }
            if (i > 2 && i < 7) {
                let type = "text";
                if (i == 0) {
                    type = "number";
                } else if (i == 1 || i == 5) {
                    type = "date";
                }
                if (td[i].querySelector("input")) {
                    $(td[i]).html(`<input class="form-control ${classes[i]}" ${i == 0 ? 'style="width:100px"' : ''} type="${type}" value="" onfocus='onInputFocus(this)' onclick="onInputClick(this)" onchange="updateButtonState(this)" />`);
                } else {
                    $(td[i]).html(`<input class="form-control ${classes[i]}" ${i == 0 ? 'style="width:100px"' : ''} type="${type}" value="${content}" onfocus='onInputFocus(this)' onclick="onInputClick(this)" onchange="updateButtonState(this)" />`);
                }
            }
        }
        if (!valid) {
            btn.addClass("disabled");
        }

    }

}

let autoUpdateTimeout = [];

function updateButtonState(elm) {
    let tr = elm.parentElement.parentElement;
    let input = tr.querySelectorAll("input");
    let valid = true;
    if(autoUpdateTimeout.hasOwnProperty(tr.getAttribute("data-id"))){
        clearTimeout(autoUpdateTimeout[tr.getAttribute("data-id")]);
    }
    for (let i of input) {
        if (i.classList.contains("observation-client")) {
            continue;
        } else if (i.value == "") {
            valid = false;
        }
    }
    let btn = tr.querySelector(".save-edit-row");
    if (valid) {
        btn.classList.remove("disabled");
        autoUpdateTimeout[tr.getAttribute("data-id")] = setTimeout(function () {
            updateRow(btn);
        }, 1400);
    } else {
        btn.classList.add("disabled");
    }
}

function onInputClick(elm) {
    let tr = elm.parentElement.parentElement;
    if(autoUpdateTimeout.hasOwnProperty(tr.getAttribute("data-id"))){
        clearTimeout(autoUpdateTimeout[tr.getAttribute("data-id")]);
    }
}

function onInputFocus(elm) {
    let tr = elm.parentElement.parentElement;
    if(autoUpdateTimeout.hasOwnProperty(tr.getAttribute("data-id"))){
        clearTimeout(autoUpdateTimeout[tr.getAttribute("data-id")]);
    }
}

function onOvserverFocus(elm) {
    let tr = elm.parentElement.parentElement;
    if(autoUpdateTimeout.hasOwnProperty(tr.getAttribute("data-id"))){
        clearTimeout(autoUpdateTimeout[tr.getAttribute("data-id")]);
    }
}

function lastPage() {
    let room_id = $("#room_id");
    let room_type = $("#room_type");
    let firstname = $("#firstname");
    let lastname = $("#lastname");
    let date = $("#date");

    postRequet(room_id, room_type, firstname, lastname, date, function (msg) {
        var t = $('#datatable').DataTable();
        t.page(t.page.info().pages - 1).draw(false);
        room_id.val('');
        room_type.val('');
        firstname.val('');
        lastname.val('');
        date.val('');
    });

}


function postRequet(room_id, room_type, firstname, lastname, date, callback, id = false) {
    if (room_id.val() && room_type.val() && firstname.val() && date.val()) {
        let data = {
            type: "update",
            room_id: room_id.val(),
            room_type: room_type.val(),
            firstname: firstname.val(),
            lastname: lastname.val(),
            date: date.val(),
            id
        };
        // console.log(data);
        $.post('../views/data', data)
            .done(function (msg) {

                if (msg == 'Added.' || msg == 'Updated.') {
                    renderSelect();
                    callback(msg);
                } else
                    swal("Error", msg, "error");

            })
            .fail(function (xhr, status, error) {
                swal("Error", (status || 'Not responsed!'), "error");
            });

    } else
        swal("Error", 'Validation Error', "error");
}


let msg = document.createElement("div");
let msgP = document.createElement("p");
msg.classList.add("msg");
msg.insertAdjacentElement("beforeend", msgP);

function showMsg(textMsg) {
    let container = document.getElementById("content-container");
    msgP.innerText = textMsg;
    container.appendChild(msg);
}

function hideMsg() {
    setTimeout(() => {
        msg.classList.add("open");
    }, 20);
    setTimeout(() => {
        msg.classList.remove("open");
    }, 2000);
    setTimeout(() => {
        msg.remove();
    }, 2100);
}

$(document).ready(function () {
    $.post("../views/data",
        {
            type: "roomId"
        },
        function (data, status) {
            let json = JSON.parse(data);
            if (json.status === "success") {
                let room_num_f = document.getElementById("room_num_f");
                for (let row in json.data) {
                    room_num_f.insertAdjacentHTML("beforeend", "<option value='" + json.data[row] + "'>" + String(json.data[row]).replaceAll("\"", "") + "</option>");
                }
            }
        });

    checkStatus();

    let container = document.getElementsByClassName("container")[0];

    let confirm = document.getElementById("confirm");
    confirm.addEventListener("click", () => {
        if (confirm.classList.contains("disabled")) {
            return;
        }
        $.post("../views/data",
            {
                type: "confirm"
            },
            function (data, status) {
                let json = JSON.parse(data);
                if (json.status === "success") {
                    showMsg("E-mail sent successfully");
                    let room_num_f = document.getElementById("room_num_f");
                    for (let row in json.data) {
                        room_num_f.insertAdjacentHTML("beforeend", "<option>" + json.data[row] + "</option>");
                    }
                } else {
                    showMsg("E-mail sent failed");
                }
                hideMsg();
            });
    });
    let color = [
        "#fcfcfc",
        "#c9c9c9",
        "#9f9f9f",
    ];

    let colorAt = 0;

    let colored = [];

    $('#datatable').dataTable({
        "searching": false,
        "processing": false,
        "serverSide": true,
        'serverMethod': 'post',
        "stripeClasses": [],
        //  "responsive": true,
        "lengthChange": true,
        "pageLength": 10,
        "order": [],
        // "dom": '<"row view-filter"<"col-sm-12"<"pull-left"lr><"pull-right"f><"clearfix">>>t<"pull-left"p><"text-right"i>',
        "ajax": {
            "url": "../views/data",
            "type": "post",
            "data": function (d) {
                colored = [];
                d.type = "get";
                d.query = $('#query_f').val();
                d.date = $('#date_f').val();
                d.room_num = $('#room_num_f').val();
                return d;
            }
        },
        'createdRow': function (row, data, dataIndex) {
            $(row).attr('data-id', data.id);
            if (colored.hasOwnProperty(data.no)) {
                row.style.background = colored[data.no];
            } else {
                row.style.background = color[colorAt];
                colored[data.no] = color[colorAt];
                colorAt++;
            }
            if (colorAt === color.length) {
                colorAt = 0;
            }
        },
        aoColumns: [
            {
                "data": 'room_num',
                "bSortable": true,
                "mRender": function (data, type, row) {
                    return String(row.no).replaceAll("\"", "");
                }
            },
            {
                "data": 'arrive_date',
                "bSortable": true,
                "mRender": function (data, type, row) {
                    return row.arrive_date;
                }
            },
            {
                "data": 'room_type',
                "bSortable": true,
                "mRender": function (data, type, row) {
                    return row.type;
                }
            },
            {
                "data": 'firstname',
                "bSortable": true,
                "mRender": function (data, type, row) {
                    if (row.firstname === "" || row.lastname === "" || row.birthday === "") {
                        return '<input onfocus="onInputFocus(this)" onclick="onInputClick(this)" onchange="updateButtonState(this)" id=\"firstname\" type=\"text\" class=\"form-control first-name\" value=\""+row.firstname+"\">';
                    } else {
                        return row.firstname;
                    }
                }
            },
            {
                "data": 'lastname',
                "bSortable": true,
                "mRender": function (data, type, row) {
                    if (row.firstname === "" || row.lastname === "" || row.birthday === "") {
                        return '<input onfocus="onInputFocus(this)" onclick="onInputClick(this)" onchange="updateButtonState(this)" id=\"lastname\" type=\"text\" class=\"form-control last-name\" value=\""+row.lastname+"\">';
                    } else {
                        return row.lastname;
                    }
                }
            },
            {
                "data": 'date',
                "bSortable": true,
                "mRender": function (data, type, row) {
                    if (row.firstname === "" || row.lastname === "" || row.birthday === "") {
                        return '<input onfocus="onInputFocus(this)" onclick="onInputClick(this)" onchange="updateButtonState(this)" id=\"date\" type=\"date\" class=\"form-control birthday\" value=\""+row.birthday+"\">';
                    } else {
                        return row.birthday;
                    }
                }
            },

            {
                "data": 'observation',
                "bSortable": true,
                "mRender": function (data, type, row) {
                    if (row.observation_client.trim() === "" && (row.firstname === "" || row.lastname === "" || row.birthday === "")) {
                        return "<input onfocus=\"onInputFocus(this)\" onclick=\"onInputClick(this)\" onchange='updateButtonState(this)' id=\"observation-client\" type=\"text\" class=\"form-control observation-client\" value=\"" + row.observation_client + "\">";
                    } else {
                        return row.observation_client;
                    }
                }
            },

            {
                "data": null,
                "bSortable": false,
                //  "searchable": false,
                "mRender": function (data, type, row) {
                    if (row.firstname === "" || row.lastname === "" || row.birthday === "") {
                        return `<button class="save-edit-row btn btn-primary disabled edit-btn" onclick="editFun(this,${data.id})"><img class="fit-picture"
                        src="../medias/fleche-vers-le-haut-de-sauvegarde-dans-le-cloud.png"
                        alt="save-icons"></button>`;
                    } else {
                        return `<button class="save-edit-row btn " onclick="editFun(this,${data.id})"> <img class="fit-picture"
                        src="../medias/edition.png"
                        alt="edit-icons"></button>`;
                    }
                }

            }
        ]
    });

});

function checkStatus() {
    $.post("../views/data",
        {
            type: "getStatus"
        },
        function (data, status) {
            let json = JSON.parse(data);
            if (json.status === "success") {
                let confirm = document.getElementById("confirm");
                if (json.confirm) {
                    confirm.classList.remove("disabled");
                }
            }
        });
}

function updateRow(elm) {
    let tr = elm.parentElement.parentElement;
    let firstname = tr.querySelector(".first-name").value;
    let lastname = tr.querySelector(".last-name").value;
    let birthday = tr.querySelector(".birthday").value;
    let arriveDate = null;
    if (tr.querySelector(".arrive-date") == null) {
        arriveDate = "0000/00/00";
    } else {
        arriveDate = tr.querySelector(".arrive-date").value;
    }
    let observationClient = tr.querySelector(".observation-client").value;

    let formData = new FormData();
    formData.append("type", "update");
    formData.append("id", tr.getAttribute("data-id"));
    formData.append("firstname", firstname);
    formData.append("lastname", lastname);
    formData.append("birthday", birthday);
    formData.append("observation-client", observationClient);
    let http = new Http("../views/data", "POST", formData);
    http.onSuccessCallback((data) => {
        let json = JSON.parse(data);
        console.log(json);
        checkStatus();
        elm.classList.remove('btn-primary');
        elm.classList.remove('disabled');
        elm.innerHTML = '<img class="fit-picture"src="../medias/edition.png"alt="save-icons"></button>';
        let td = tr.querySelectorAll('td');
        for (let i = 0; i < td.length; i++) {
            let input = td[i].querySelector("input");
            if (input != null) {
                td[i].innerHTML = input.value;
            }
        }
        showMsg(json.message);
        hideMsg();
    });
    http.start();
}