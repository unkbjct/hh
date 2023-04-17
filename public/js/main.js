$(document).ready(() => {
    $(".validation").focus(function () {
        $(this).removeClass("is-invalid")
    })
    $(".validation").blur(function () {
        if (this.value == '' && !this.dataset.addList) {
            console.log(this.dataset.addList)
            $(this).addClass("is-invalid")
        }
    })
    $("select").change(function () {
        if (this.value == '' && !this.classList.contains("filter")) {
            $(this).addClass("is-invalid")
        } else {
            $(this).removeClass("is-invalid");
        }
    })
    $("#city-input").on("input", function () {
        if (!this.value) return;
        // alert()
        $.ajax({
            url: `${window.location.protocol}//${window.location.hostname}/api/city/find`,
            method: 'GET',
            data: {
                city: this.value,
            },
            success: function (cities) {
                // console.log(cities)
                $("#cities-list")[0].innerHTML = null;
                cities.forEach(city => {
                    $("#cities-list").append(`<a href="${window.location.protocol}//${window.location.hostname}/api/city/${city.id}/set" class="list-group-item list-group-item-action">${city.address}</a>`);
                });
            },
            error: function (e) {
                console.log(e)
            }
        })
    })

    $("#city-form-input").on("input", function () {
        if (!this.value) {
            $(this).next().empty()
            return;
        };
        $(this).next().empty()

        $.ajax({
            url: `${window.location.protocol}//${window.location.hostname}/api/city/find`,
            method: 'GET',
            data: {
                city: this.value,
            },
            success: function (cities) {
                cities.forEach(city => {
                    let cityTitle = (city.city) ? city.city : city.region;
                    let btn = document.createElement("button");
                    btn.classList.add("w-100");
                    btn.classList.add("list-group-item");
                    btn.classList.add("list-group-item-action");
                    btn.textContent = cityTitle;
                    btn.type = "button";
                    btn.addEventListener("click", function () {
                        $(this).prev().val(city.id);
                        $(this).prev().trigger('change');
                        $(this).val(cityTitle);
                        $(this).next().empty()

                    }.bind(this))
                    $(this).next().append(btn)
                });
            }.bind(this),
            error: function (e) {
                console.log(e)
            }
        })
    })

    // $("#city-form-input").on("blur", function (e) {
    //     console.log(e)
    //     if (!this.value) return;
    //     // $(this).next().empty()
    // })


    $(".create-response").click(function () {
        let url = `${window.location.protocol}//${window.location.hostname}/api/vacancy/${this.dataset.vacancyId}/response`
        // alert(url)
        $.ajax({
            method: 'POST',
            url: url,
            data: {},
            success: function (e) {
                console.log(e)
                $("#good-response-position").text(e.data.vacancy.position)
                const modal = new bootstrap.Modal(document.getElementById('good-response'))
                document.getElementById('good-response').addEventListener("hidden.bs.modal", function () {
                    location.reload();
                })
                modal.show();
            },
            error: function (e) {
                console.log(e)
                $("#bad-response-position").text(e.responseJSON.data.vacancy.position)
                const modal = new bootstrap.Modal(document.getElementById('bad-response'))
                document.getElementById('bad-response').addEventListener("hidden.bs.modal", function () {
                    location.reload();
                })
                modal.show();
            }
        })
    })

    $(".favorite-btn").click(function () {
        let id = this.dataset.id,
            type = this.dataset.type,
            url = `${window.location.protocol}//${window.location.hostname}/api/favorite`;

        $.ajax({
            method: "POST",
            url: url,
            data: {
                id: id,
                type: type,
            },
            success: function (e) {
                console.log(e);
                // location.reload();
                (e.data.action == 'remove') ? this.classList.remove('active') : this.classList.add('active');
                // alert(window.location.href)
                if(window.location.href == `${window.location.protocol}//${window.location.hostname}/personal/favorites`) location.reload();
            }.bind(this),
            error: function (e) {
                console.log(e)
            }
        })

    })


    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
})

const showMessage = (options) => {
    // $("#modal .modal-body").empty()

    // $("#modal .modal-title").text(options.title)

    // let content = $("#modal .modal-content");
    // content.removeAttr("class")
    // content.addClass("modal-content")
    // content.addClass(`bg-${options.type}-subtle`);

    for (let key in options.messageList) {
        // $("#modal .modal-body")[0].insertAdjacentHTML("beforeend", `<p>${options.messageList[key]}</p>`)
        if (options.type == 'danger') $("#" + key).addClass("is-invalid");
    }
}