var userApp = angular.module('userApp', ['ngSanitize']);
userApp.controller('UserController', ['$scope', '$http', "sharedVariables", 'colorFilter', function ($scope, $http, sharedVariables, colorFilter) {
    $scope.people = {
        persondata: []
    };
    $scope.name = "";
    $scope.surname = "";
    $scope.age = "";
    $scope.email = "";
    $scope.searchedText = "";
    $scope.highlightedText = "";

    

    $scope.orderProp = 'name';
    //console.log($scope.orderProp);
    $scope.setOrderProp = function (orderProp) {
        if ($scope.orderProp === orderProp) {
            $scope.orderProp = '-' + $scope.orderProp;
        } else if ($scope.orderProp === '-' + orderProp) {
            $scope.orderProp = orderProp;
        } else {
            $scope.orderProp = orderProp;
        }
    }

    $scope.color = colorFilter($scope.name, $scope.highlightedText);

    $scope.deletePerson = function(id) {
        var json = {action:"delete-person", id:id};
        console.log(json);
        $http.post("g.php", {json: JSON.stringify(json)})
             .success(function(response){
                 $scope.people.persondata = response;
             })
             .error(function(response){

             });
    }

    $scope.loadData = function () {
        var json = {
            action: "get-people"
        };
        $http.post("g.php", {
                json: JSON.stringify(json)
            })
            .success(function (response) {
                //console.log(response);
                //$scope.people = response;
                sharedVariables.setPeople(response);
                $scope.people.persondata = sharedVariables.getPeople();
            })
            .error(function (response) {
                //Not yet implemented
            });
    }

    $scope.loadData();

}]);

userApp.filter('color', ["$sce", function ($sce) {
    return function (str, highlightedText) {
        if (highlightedText.length == 0) return str;
        var rtext = '<span style="color: rgba(0,168,107,0.99);">' + highlightedText + '</span>';
        var regex = new RegExp(highlightedText, 'g');
        //console.log(str+"->"+highlightedText);
        str = str.replace(regex, rtext);
        return $sce.trustAsHtml(str);
    }
}]);

userApp.controller('NewUserController', ["$scope", "$http", "sharedVariables", function ($scope, $http, sharedVariables) {
    $scope.newname = "";
    $scope.newsurname = "";
    $scope.newage = "";
    $scope.newemail = "";

    console.log($scope);
    $scope.addPerson = function () {
        var json = {
            action: "add-person",
            name: $scope.newname,
            surname: $scope.newsurname,
            age: $scope.newage,
            email: $scope.newemail
        };
        $http.post("g.php", {
                json: JSON.stringify(json)
            })
            .success(function (response) {
                sharedVariables.setPeople(response);
            })
            .error(function (response) {

            });
    }
    
}]);

userApp.service('sharedVariables', function () {
    var people = [];
    return {
        getPeople: function () {
            return people;
        },
        setPeople: function (p) {
            people = p;
        }
    }
});