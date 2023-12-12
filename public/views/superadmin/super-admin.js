function success(message) {
    Swal.fire({
      title: "Good job!", // Add the title "Good job!"
      text: message,
      icon: "success",
      timer: 5000,
      showConfirmButton: true,
    });
  }


  function failed(message) {
    Swal.fire({
      title: "Failed job!", 
      text: message,
      icon: "error", // Corrected "warrning" to "error" for the icon type
      timer: 5000,
      showConfirmButton: true,
    });
  }
  