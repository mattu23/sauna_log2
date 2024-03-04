<template>
  <div :id="'map-' + logId" class="sauna-map"></div>
</template>

<style scoped>
.sauna-map {
  width: 100%;
  height: 400px; /* 必要に応じて調整 */
}
</style>


<script>
// SaunaMap.vue のスクリプト部分

export default {
  props: {
    logId: {
      type: Number,
      required: true,
    },
    saunaName: {
      type: String,
      required: true,
    },
  },
  mounted() {
    this.createMap();
  },
  methods: {
    createMap() {
      const map = new google.maps.Map(document.getElementById(`map-${this.logId}`), {
        center: { lat: -34.397, lng: 150.644 },
        zoom: 15,
      });
      const request = {
        query: this.saunaName,
        fields: ['name', 'geometry'],
      };

      const service = new google.maps.places.PlacesService(map);
      service.findPlaceFromQuery(request, (results, status) => {
        if (status === google.maps.places.PlacesServiceStatus.OK && results[0]) {
          map.setCenter(results[0].geometry.location);

          new google.maps.Marker({
            map: map,
            position: results[0].geometry.location
          });
        }
      });
    },
  },
}


</script>
