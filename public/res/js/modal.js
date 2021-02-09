function showModal(index) {
    const hID = [
        "modalH01",
        "modalH02",
        "modalH03",
        "modalH04",
        "modalH05",
        "modalH06",
    ];
    const bID = [
        "modalB01",
        "modalB02",
        "modalB03",
        "modalB04",
        "modalB05",
        "modalB06",
    ];
    const fID = [
        "modalF01",
        "modalF02",
        "modalF03",
        "modalF04",
        "modalF05",
        "modalF06",
    ];
    const allArrays = [hID, bID, fID];
    // "none" / "initial";
    for (let arr of allArrays) {
        for (let x of arr) {
            const elem = document.getElementById(x);
            elem.style.display = "none";
        }
    }
    for (let arr of allArrays) {
        const elem = document.getElementById(arr[index]);
        elem.style.display = "initial";
    }
    $("#myModal").modal()
}



