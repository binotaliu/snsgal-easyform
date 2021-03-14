<template>
      <twzipcode
        :class-names="{county: 'form-control', district: 'form-control', zipcode: 'form-control'}"
        :names="{county: 'county', district: 'city', zipcode: 'postcode'}"
        default-zipcode="100"
        ref="twzipcode"
        @update="result"
      >
      </twzipcode>
</template>

<script>
  import Twzipcode from 'vue2-twzipcode';

  export default {
    methods: {
      result({ zipcode, county, district}) {
      }
    },
    components: {
      Twzipcode
    }
  };
</script>