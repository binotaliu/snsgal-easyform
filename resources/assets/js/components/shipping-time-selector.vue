<template>
    <div>
        <input type="hidden" v-model="timeValue" v-bind:name="inputName">
        <input type="checkbox" v-model="morning"> 9~12<br>
        <input type="checkbox" v-model="noon"> 12~17<br>
        <input type="checkbox" v-model="night"> 17~20<br>
        <input type="checkbox" v-model="late_night"> 20~21<br>
    </div>
</template>
<style>
</style>
<script>
    export default {
        data() {
            return {
                morning: true,
                noon: true,
                night: true,
                late_night: true
            }
        },
        props: {
            inputName: {
                type: String,
                default: 'time'
            }
        },
        computed: {
            timeValue() {
                let retval = 0;
                if (this.morning) retval |= 1;
                if (this.noon) retval |= 2;
                if (this.night ) retval |= 4;
                if (this.late_night) retval |= 8;
                return retval;
            }
        },
        methods: {
            showValues() {
              console.log(this.timeValue);
            }
        }
    }
</script>
