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
                console.log(response);
                if (response === 'DuplicateFounded') {
                    addProvince.style.display = "none";
                    alert('نام مدرسه تکراری وارد شده است.');
                    return false;
                } else {
                    alert('مدرسه با موفقیت اضافه شد.');
                    location.reload();
                }
            }
        });
    }
}

document.getElementById('provincesForCheck').oninput = function () {
    $.ajax({
        url: "./build/ajax/ProvincesManager.php",
        type: "GET",
        data: {
            work: "gettingResult",
            center: centers.value,
            state: states.value,
            city: cities.value,
            gender: gender.value,
        },
        success: function (response) {
            result.innerHTML=response;
        }
    });
}