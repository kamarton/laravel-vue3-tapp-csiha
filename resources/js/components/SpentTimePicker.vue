<script setup lang="ts">

defineProps({
    seconds: {
        type: Number,
        default: 0,
    },
    readonly: {
        type: Boolean,
        default: false,
    },
});
</script>
<script lang="ts">

export default {
    name: "SpentTimePicker",
    emits: ['update'],
    data() {
        return {
            days: 0,
            hours: 0,
            minutes: 0,
        }
    },
    computed: {},
    methods: {
        changeTime() {
            this.$emit('update',
                (this.days ?? 0) * 24 * 60 * 60 +
                (this.hours ?? 0) * 60 * 60 +
                (this.minutes ?? 0) * 60
            )
        },
        parseTime(seconds: Number) {
            this.days = Math.floor(seconds / (24 * 60 * 60));
            this.hours = Math.floor((seconds % (24 * 60 * 60)) / (60 * 60));
            this.minutes = Math.floor((seconds % (60 * 60)) / 60);
        },
        prependNumber(n: Number | string) {
            let v = parseInt(n);
            if (isNaN(v) || v < 0) {
                v = 0;
            }
            return v;
        },
    },
    watch: {
        seconds(newValue, _) {
            this.parseTime(newValue);
        },
        days(newValue, _) {
            this.days = this.prependNumber(newValue);
        },
        hours(newValue, _) {
            this.hours = this.prependNumber(newValue);
        },
        minutes(newValue, _) {
            this.minutes = this.prependNumber(newValue);
        },
    },
}
</script>

<template>
    <v-row>
        <v-col>
            <v-text-field
                :readonly="readonly"
                v-model="days"
                label="Days"
                type="number"
                @change="changeTime"
            />
        </v-col>
        <v-col>
            <v-text-field
                :readonly="readonly"
                v-model="hours"
                label="Hours"
                type="number"
                @change="changeTime"
            />
        </v-col>
        <v-col>
            <v-text-field
                :readonly="readonly"
                v-model="minutes"
                label="Minutes"
                type="number"
                @change="changeTime"
            />
        </v-col>
    </v-row>
</template>

<style scoped>

</style>
