function searchInc() {
    $.ajax({
        url: "./build/ajax/ProvincesManager.php",
        type: "GET",
        data: {
            work: "gettingResult",
            center: centersForCheck.value,
            state: statesForCheck.value,
            city: citiesForCheck.value,
            gender: genderForCheck.value,
        },
        success: function (response) {
            result.innerHTML = response;
            var deactiveButtons = document.querySelectorAll('#deactive');
            deactiveButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    $.ajax({
                        url: "./build/ajax/ProvincesManager.php",
                        type: "POST",
                        data: {
                            work: "deactiveProvince",
                            province: this.value,
                        },
                        success: function (response) {
                            if (response === 'Done') {
                                searchInc();
                                alert('عملیات با موفقیت انجام شد.');
                            }
                        }
                    });
                });
            });

            var activeButtons = document.querySelectorAll('#active');
            activeButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    $.ajax({
                        url: "./build/ajax/ProvincesManager.php",
                        type: "POST",
                        data: {
                            work: "activeProvince",
                            province: this.value,
                        },
                        success: function (response) {
                            if (response === 'Done') {
                                searchInc();
                                alert('عملیات با موفقیت انجام شد.');
                            }
                        }
                    });
                });
            });
        }
    });
}

function searchInProvincesInfo() {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchInTable");
    filter = input.value.toUpperCase();
    table = document.getElementById("provincesInfo");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[4];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

document.getElementById('centers').onchange = function () {
    $.ajax({
        url: "./build/ajax/ProvincesManager.php",
        type: "GET",
        data: {
            work: "getStates",
            center: this.value
        },
        success: function (response) {
            var statesSelect = document.getElementById("states");
            statesSelect.innerHTML = "";
            var defaultOption = new Option("انتخاب کنید", "");
            defaultOption.disabled = true;
            defaultOption.selected = true;
            statesSelect.add(defaultOption);

            var citiesSelect = document.getElementById("cities");
            citiesSelect.innerHTML = "";
            var defaultOption = new Option("انتخاب کنید", "");
            defaultOption.disabled = true;
            defaultOption.selected = true;
            citiesSelect.add(defaultOption);

            var states = JSON.parse(response);
            states.forEach(function (states) {
                statesSelect.add(new Option(states, states));
            });
        }
    });
}

document.getElementById('states').onchange = function () {
    $.ajax({
        url: "./build/ajax/ProvincesManager.php",
        type: "GET",
        data: {
            work: "getCities",
            center: centers.value,
            state: this.value
        },
        success: function (response) {
            var citiesSelect = document.getElementById("cities");
            citiesSelect.innerHTML = "";
            var defaultOption = new Option("انتخاب کنید", "");
            defaultOption.disabled = true;
            defaultOption.selected = true;
            citiesSelect.add(defaultOption);

            var cities = JSON.parse(response);
            cities.forEach(function (cities) {
                citiesSelect.add(new Option(cities, cities));
            });
        }
    });
}

document.getElementById('school').oninput = function () {
    addProvince.style.display = "";
    $.ajax({
        url: "./build/ajax/ProvincesManager.php",
        type: "GET",
        data: {
            work: "checkSchool",
            center: centers.value,
            state: states.value,
            city: cities.value,
            school: this.value
        },
        success: function (response) {
            if (response === 'DuplicateFounded') {
                addProvince.style.display = "none";
                alert('نام مدرسه تکراری وارد شده است.');
                return false;
            }
        }
    });
}

document.getElementById('addProvince').onclick = function () {
    if (!centers.value) {
        alert('مرکز انتخاب نشده است');
        return false;
    } else if (!states.value) {
        alert('استان انتخاب نشده است');
        return false;
    } else if (!cities.value) {
        alert('شهرستان انتخاب نشده است');
        return false;
    } else if (!school.value) {
        alert('مدرسه وارد نشده است');
        return false;
    } else if (!gender.value) {
        alert('جنسیت وارد نشده است');
        return false;
    } else if (confirm('آیا مطمئن هستید؟')) {
        $.ajax({
            url: "./build/ajax/ProvincesManager.php",
            type: "POST",
            data: {
                work: "addSchool",
                center: centers.value,
                state: states.value,
                city: cities.value,
                school: school.value,
                gender: gender.value,
            },
            success: function (response) {
                if (response === 'DuplicateFounded') {
                    addProvince.style.display = "none";
                    alert('نام مدرسه تکراری وارد شده است.');
                    return false;
                } else {
                    alert('مدرسه با موفقیت اضافه شد.');
                }
            }
        });
    }
}


document.getElementById('centersForCheck').onchange = function () {
    $.ajax({
        url: "./build/ajax/ProvincesManager.php",
        type: "GET",
        data: {
            work: "getStates",
            center: this.value
        },
        success: function (response) {
            var statesSelect = document.getElementById("statesForCheck");
            statesSelect.innerHTML = "";
            var defaultOption = new Option("بدون فیلتر", "");
            defaultOption.selected = true;
            statesSelect.add(defaultOption);

            var citiesSelect = document.getElementById("citiesForCheck");
            citiesSelect.innerHTML = "";
            var defaultOption = new Option("بدون فیلتر", "");
            defaultOption.selected = true;
            citiesSelect.add(defaultOption);

            var states = JSON.parse(response);
            states.forEach(function (states) {
                statesSelect.add(new Option(states, states));
            });
        }
    });
}

document.getElementById('statesForCheck').onchange = function () {
    $.ajax({
        url: "./build/ajax/ProvincesManager.php",
        type: "GET",
        data: {
            work: "getCities",
            center: centersForCheck.value,
            state: this.value
        },
        success: function (response) {
            var citiesSelect = document.getElementById("citiesForCheck");
            citiesSelect.innerHTML = "";
            var defaultOption = new Option("بدون فیلتر", "");
            defaultOption.selected = true;
            citiesSelect.add(defaultOption);

            var cities = JSON.parse(response);
            cities.forEach(function (cities) {
                citiesSelect.add(new Option(cities, cities));
            });
        }
    });
}

document.getElementById('search').onclick = function () {
    if (centersForCheck.value) {
        searchInc();
    } else {
        alert('مرکز را انتخاب نمایید.');
        return false;
    }
}
