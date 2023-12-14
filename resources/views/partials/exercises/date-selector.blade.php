<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/plugins/monthSelect/style.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/plugins/weekSelect/weekSelect.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/plugins/monthSelect/index.js"></script>

<div>
    <div class="col-span-3">
        <label class="block font-medium text-sm text-gray-700" for="planSelected">
            Select Week
        </label>
        <input
            id="weekSelector"
            class="flatpickr flatpickr-input active mt-1 block border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block"
            type="date" placeholder="Select Week.." readonly="readonly">
    </div>
    <div class="col-span-3">
        <label class="block font-medium text-sm text-gray-700" for="planSelected">
            Select Month
        </label>
        <input
            id="monthSelector"
            class="flatpickr flatpickr-input active mt-1 block border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block"
            type="date" placeholder="Select Month.." readonly="readonly">
    </div>
    <br/>
</div>
<script>

    flatpickr('#weekSelector', {
        plugins: [new weekSelect({})],
        onChange: [function () {
            // extract the week number
            // note: "this" is bound to the flatpickr instance
            const weekNumber = this.selectedDates[0]
                ? this.config.getWeek(this.selectedDates[0])
                : null;

            start = new moment().day("Monday").isoWeek(weekNumber);
            end = new moment().day("Sunday").isoWeek(weekNumber)

            startStr = start.format('Y-M-D');
            endStr = end.format('Y-M-D');
            //console.log(weekNumber);
            //console.log( '/exercises/view/' + startStr + ":" + endStr);
            window.location.href = '/exercises/view/' + startStr + ":" + endStr;

        }],
        defaultDate: 'today',
        locale: {
            "firstDayOfWeek": 1 // start week on Monday
        }
    });
    flatpickr('#monthSelector', {
        "plugins": [
            new monthSelectPlugin({
                shorthand: true, //defaults to false
                dateFormat: "m.y", //defaults to "F Y"
                altFormat: "F Y", //defaults to "F Y"
                theme: "dark" // defaults to "light"
            })
        ],
        "onChange": [function () {
            // extract the week number
            // note: "this" is bound to the flatpickr instance

            console.log("Start: " + this.selectedDates[0] + "End: " + this.selectedDates[1]);
            month = new moment(this.selectedDates[0]);

            startStr = month.clone().startOf('month').format('Y-M-D');
            endStr = month.clone().endOf('month').format('Y-M-D');
            //console.log( '/exercises/view/' + startStr + ":" + endStr);
            window.location.href = '/exercises/view/' + startStr + ":" + endStr;
        }],
        defaultDate: 'today',

    });

</script>
