<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.2/angular.min.js"></script>
</head>

<body ng-app="myApp" ng-controller="myCtrl" ng-init="loadData()">
    <div ng-if="dataLoaded">
        <!-- Success message for successful submission -->
        <div ng-if="successMessage" class="alert alert-success" role="alert">
            @{{ successMessage }}
        </div>

        <!-- Form for submitting data -->
        <form ng-submit="submitFormData()">
            <div class="form-group">
                <label for="target">Target:</label>
                <input type="text" class="form-control" id="target" ng-model="target">
            </div>
            <div class="form-group">
                <label for="exclude_target">Excluded Target:</label>
                <input type="text" class="form-control" id="exclude_target" ng-model="exclude_target">
            </div>
            <!-- Error messages for form validation -->
            <div ng-if="errors">
                <div class="alert alert-danger" role="alert">
                    <p ng-repeat="error in errors">@{{ error }}</p>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        
        <!-- Table for displaying data -->
        <table class="table">
            <!-- Table header -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Excluded Target</th>
                    <th>Target Domain</th>
                    <th>Referring Domain</th>
                    <th>Rank</th>
                    <th>Backlinks</th>
                </tr>
            </thead>
            <!-- Table body -->
            <tbody>
                <!-- Displaying each data row -->
                <tr ng-repeat="target in targets">
                    <td>@{{ target.id }}</td>
                    <td>@{{ target.excluded_target }}</td>
                    <td>@{{ target.target_domain }}</td>
                    <td>@{{ target.referring_domain }}</td>
                    <td>@{{ target.rank }}</td>
                    <td>@{{ target.backlinks }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="row">
            <div class="col">
                <h3>Pages:</h3>
            </div>
            <div class="col">
                <ul class="list-unstyled d-inline">
                    <li ng-repeat="link in links" class="d-inline">
                        <a href="#" ng-click="loadPage(link.url)" class="d-inline">@{{ link.label }}</a>
                    </li>
                </ul>
            </div>
        </div>

    <script>
        var app = angular.module('myApp', []);
        app.controller('myCtrl', function($scope, $http) {
            $scope.loadData = function() {
                $http.get('api/targets/list?page=1')
                    .then(function(response) {
                        console.log(response.data);
                        $scope.targets = response.data.data;
                        $scope.links = parseLinks(response.data.links); // Parsing links from the response
                        $scope.dataLoaded = true;
                    })
                    .catch(function(error) {
                        console.error('Error fetching data:', error);
                    });
            };

            $scope.loadPage = function(url) {
                $http.get(url.url)
                    .then(function(response) {
                        $scope.targets = response.data.data;
                        $scope.links = parseLinks(response.data.links);
                    })
                    .catch(function(error) {
                        console.error('Error fetching data:', error);
                    });
            };

            // Function to parse links from the server response
            function parseLinks(links) {
                var parsedLinks = [];
                for (var key in links) {
                    parsedLinks.push({
                        label: key,
                        url: links[key]
                    });
                }
                return parsedLinks;
            }

            // Function to submit form data
            $scope.submitFormData = function() {
                var formData = new FormData();
                formData.append('targets[]', $scope.target);
                formData.append('exclude_targets[]', $scope.exclude_target);

                // Setting the correct Content-Type header
                var config = {
                    headers: {
                        'Content-Type': undefined
                    },
                    transformRequest: angular.identity
                };

                $http.post('api/targets/add', formData, config)
                    .then(function(response) {
                        console.log(response.data);
                        $scope.successMessage = 'Data successfully submitted!';
                    })
                    .catch(function(error) {
                        console.error('Error submitting form data:', error);
                        if (error.data) {
                            var errorMessages = [];
                            for (var key in error.data) {
                                if (error.data.hasOwnProperty(key)) {
                                    errorMessages = errorMessages.concat(error.data[key]);
                                }
                            }
                            $scope.errors = errorMessages;
                        } else {
                            $scope.errors = ["An unknown error occurred."];
                        }
                    });
            };

        });
    </script>

</body>

</html>
