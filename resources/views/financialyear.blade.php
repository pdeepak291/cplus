<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Year</title>
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" >
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h5>Financial Years</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-sm-4">
                    <div class="form-group">
                        <label for="country">Country</label>
                        <select class="form-control" id="country">
                            <option value="">Select Country</option>
                            <option value="GB">UK</option>
                            <option value="IE">Ireland</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-sm-8">
                    <label for="year">Year</label>
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <select class="form-control" id="year">
                                    <option value="">Select Year</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <button class="btn btn-primary" id="getData">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 p-2">
                    <div id="fyear"></div>
                    <ul id="holidays"></ul>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            const curyear = new Date().getFullYear();
            const years = [];
            for(let i=curyear-10;i<=curyear;i++){
                years.push(i);
            }
            
            $("#country").change(function(){
                const country = $(this).val();
                $("#year").empty();
                $("#year").append('<option value="">Select Year</select>');
                if(country==='IE'){
                    years.forEach(function(year) {
                        $("#year").append('<option value="'+year+'">'+year+'</select>');
                    });
                }else if(country==='GB'){
                    years.forEach(function(year) {
                        let nextYear = year + 1;
                        $("#year").append('<option value="'+year+'">'+year+'-'+nextYear.toString().slice(-2)+'</select>');
                    });
                }
            });

            $("#getData").click(function(){
                const country =  $("#country").val();
                const year =  $("#year").val();
                $("#fyear").html('');
                $("#holidays").html('');
                $.ajax({
                    url: "{{ route('result') }}",
                    type: 'GET',
                    data: {country:country, year:year},
                    success: function(response){
                        
                        $("#fyear").html('<p><b>Financial Year Start : </b>'+response.startDate+'</p><p><b>Financial Year End : </b>'+response.endDate+'</p>');
                        const holidays = response.holiDays;

                        if (typeof holidays === 'object') {
                            const holidayArray = Object.values(holidays);

                            holidayArray.forEach(holiday => {
                                const holidayDate = new Date(holiday.date);
                                if (holidayDate.getDay() !== 0 && holidayDate.getDay() !== 6) { // Exclude Sunday (0) and Saturday (6)
                                    $("#holidays").append(`<li>${holiday.name} - ${holiday.date}</li>`);
                                }
                            });
                        } else {
                            console.error("Holidays is not an object:", holidays);
                        }
                    
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        try {
                            var jsonResponse = JSON.parse(xhr.responseText);
                            console.log(jsonResponse);
                        } catch (e) {
                            console.error("Invalid JSON response:", e);
                        }
                    }
                });
            });
        })
    </script>
</body>
</html>
