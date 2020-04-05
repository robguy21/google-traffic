<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .form-control {
                margin-bottom: 10px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2 style="margin-top: 10px;">Distance to Cape Town CBD Form</h2>
            <br>

            <form id="travel-time-form" method="post" action="javascript:void(0)">
            @csrf
                <div class="form-group" id="js-form-input-repeater">
                    <label for="formGroupExampleInput">Suburb Name</label>
                    <input type="text" name="suburb_1" class="form-control" id="formGroupExampleInput" placeholder="Name of Suburb">
                </div>
                <div id="js-prepend-target"></div>
                <div class="form-group">
                    <button type="button" id="add_suburb" class="btn btn-primary">Add Suburb</button>
                </div>
                <div class="form-group">
                    <button type="submit" id="send_form" class="btn btn-success">Get Travel Times</button>
                </div>
            </form>
            <div id="js-loading-state" style="text-align: center; font-weight: bold;"></div>
            <table id="js-datatable"></table>

        </div>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
        <script>
            const $loader = $('#js-loading-state');
            const $table = $('#js-datatable').DataTable(
                {
                    bPaginate: false,
                    paging: false,
                    ordering: false,
                    info: false,
                    searching: false,
                    data: [],
                    columns: [
                        { data: 'origin', title: 'Origin' },
                        { data: 'duration', title: 'Free-flow Travel Time'},
                        { data: 'duration_in_traffic', title: 'Congested Travel Time' },
                        { data: 'difference', title: '% extra travel time due to congestion' },
                    ],
                    columnDefs: [{
                        targets: 3,
                        createdCell(td, cellData) {
                            if (cellData > 100) {
                                $(td).css('color', 'red');
                            }
                            $(td).append('%');
                        }
                    }, {
                        targets: 0,
                        createdCell(td, cellData) {
                            $(td).css('text-transform', 'capitalize');
                        }
                    }],
                }
            );
            $(document).ready(function() {
                $('#add_suburb').on('click', function(e) {
                    e.preventDefault();
                    var $target = $('#js-form-input-repeater');
                    var inputHtml = inputHtmlGenerator($target);
                    $target.append(inputHtml);
                });

                $('#travel-time-form').on('submit', function(e) {
                    e.preventDefault();
                    setLoader(true);
                    const suburbs = $('#travel-time-form')
                        .serialize()
                        .split('&')
                        .filter(v => v.includes('suburb'))
                        .map(v => v.split('=')[1])
                        .filter(v => !!v)
                        .join(',');
                    /* $table.DataTable({ ajax: `/travel-time?suburbs=${data}` }) */
                    const apiData = getTravelTimes(suburbs).then((data) => {
                        $table.clear();
                        $table.rows.add(data);
                        $table.draw();
                    }).catch(e => alert(e)).finally(() => setLoader(false));
                })
            });

            function inputHtmlGenerator($container) {
                var num_elems = $container.find('input').length;
                var html = `
                    <input type="text" name="suburb_${num_elems}" class="form-control" placeholder="Name of Suburb">
                `;
                return html;
            }

            function getTravelTimes(suburbString) {
                return new Promise((res, rej) => {
                    $.get(`/travel-time?suburbs=${suburbString}`, (data, status) => {
                        if (status >= 300) {
                            rej('Oops! Something went wrong when contacting the API.');
                            return;
                        }
                        res(data)
                    });
                })
            }

            function setLoader(isLoading) {
                if (isLoading) {
                    $loader.text('Loading...');
                } else {
                    $loader.text('');
                }
            }
        </script>
    </body>
</html>
