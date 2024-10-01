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
                            <option value="uk">UK</option>
                            <option value="ireland">Ireland</option>
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
                    <div id="holidays"></div>
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
                if(country==='ireland'){
                    years.forEach(function(year) {
                        $("#year").append('<option value="'+year+'">'+year+'</select>');
                    });
                }else if(country==='uk'){
                    years.forEach(function(year) {
                        let nextYear = year + 1;
                        $("#year").append('<option value="'+year+'">'+year+'-'+nextYear.toString().slice(-2)+'</select>');
                    });
                }
            });

            $("#getData").click(function(){
                const country =  $("#country").val();
                const year =  $("#year").val();

                $.ajax({
                    url: "{{ route('result') }}",
                    type: 'GET',
                    data: {country:country, year:year},
                    success: function(response){ 
                        $("#fyear").html('<p><b>Financial Year Start : </b>'+response.startDate+'</p><p><b>Financial Year End : </b>'+response.endDate+'</p>');
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
