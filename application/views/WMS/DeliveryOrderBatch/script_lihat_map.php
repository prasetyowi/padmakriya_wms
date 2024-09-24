<script>
  const urlSearchParams = new URLSearchParams(window.location.search);
  const datas = Object.fromEntries(urlSearchParams.entries()).datas;
  const decodeDatas = JSON.parse(decodeURI(datas))
  console.log(decodeDatas);

  var map = L.map('datamapdobatch')
  map.setView([decodeDatas[0].latitude, decodeDatas[0].longitude], 12);
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
  }).addTo(map);

  let message = decodeDatas.map((item) => {
    return `<div>
                <h4 style="font-weight:700">Outlet : ${item.nama}</h4>
                <h5 style="font-weight:600">Prioritas ${item.prioritas}</h5>
            </div>`
  });

  L.Routing.control({
    createMarker: function(waypointIndex, waypoint) {
      return L.marker(waypoint.latLng)
        .bindPopup(message[waypointIndex]);
    },
    show: false,
    addWaypoints: false,
    draggableWaypoints: false,
    waypoints: decodeDatas.map((item) => {
      return L.latLng(item.latitude, item.longitude)
    }),
    routeWhileDragging: false
  }).addTo(map);
</script>