var ft_to_m_report = function (feet, meters, f_or_m) {


  if (f_or_m == 0){

    document.getElementById("result").innerHTML =
         feet + "ft = " + meters + "m";
  }

  if (f_or_m == 1) {
    document.getElementById("result").innerHTML =
         meters + "m = " + feet + "ft";
  }

  if (isNaN(feet))    //checks if anything but number then returns proper feedback
    document.getElementById("result").innerHTML =
      "Incorrect value entered! Please enter again";

  if (isNaN(meters))   //checks if anything but number then returns proper feedback
    document.getElementById("result").innerHTML =
      "Incorrect value entered! Please enter again";


};

var in_to_cm_report = function (inches, centimeters, in_or_cm) {


  if (in_or_cm == 0){

    document.getElementById("result").innerHTML =
         inches + "in = " + centimeters + "cm";
  }

  if (in_or_cm == 1) {
    document.getElementById("result").innerHTML =
         centimeters + "cm = " + inches + "in";
  }

  if (isNaN(inches))  //checks if anything but number then returns proper feedback
    document.getElementById("result").innerHTML =
      "Incorrect value entered! Please enter again";

  if (isNaN(centimeters))  //checks if anything but number then returns proper feedback
    document.getElementById("result").innerHTML =
      "Incorrect value entered! Please enter again";


};

document.getElementById("ft_to_m").onclick = function () {
    var f = document.getElementById("length").value;
    ft_to_m_report(f, (f * .30), 0);
};

document.getElementById("m_to_ft").onclick = function () {
    var m = document.getElementById("length").value;
    ft_to_m_report((m * 3.28), m, 1);
};

document.getElementById("in_to_cm").onclick = function () {
    var i = document.getElementById("length").value;
    in_to_cm_report(i, (i * 2.54), 0);
};

document.getElementById("cm_to_in").onclick = function () {
    var c = document.getElementById("length").value;
    in_to_cm_report((c * .394), c, 1);
};
