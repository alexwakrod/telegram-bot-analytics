import { defineStore } from 'pinia';

export const useRealtimeStore = defineStore('realtime', {
    state: () => ({
        lastEvent: null,
        events: [],
    }),
    actions: {
        addEvent(event) {
            this.events.unshift(event);
            if (this.events.length > 50) this.events.pop();
        },
    },
});