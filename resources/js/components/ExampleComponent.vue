<template>        
    <div class="grid grid-cols-2 gap-4">
        <div>
            <form @submit="filter">
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-state">
                            Estado
                        </label>
                        <div class="relative">
                            <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                v-model="selectedState" name="state" id="state"  @change="getMunicipalities($event)">
                                <option v-for="(state, index) in states" :key="state.id" :value="state.id">{{ state.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-municipality">
                            Municipio
                        </label>
                        <div class="relative">
                            <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                v-model="selectedMunicipality" name="municipality" id="municipality">
                                <option v-for="(municipality, index) in municipalities" :key="municipality.id" :value="municipality.id">{{ municipality.municipality }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-municipality">
                            Ordenar precios
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" class="form-radio" name="accountType" value="asc" v-model="sort">
                            <span class="ml-2">Ascendente</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" class="form-radio" name="accountType" value="desc" v-model="sort">
                            <span class="ml-2">Descendente</span>
                        </label>
                    </div>
                    <div class="md:flex md:items-center">
                        <div class="md:w-1/3"></div>
                        <div class="md:w-2/3">
                        <button class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded">
                            Filtrar
                        </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div>
            <gmap-map
                ref="mapRef"
                :center="center"
                :zoom="12"
                style="width:100%;  height: 400px;"
            >
            <gmap-marker
                :key="index"
                v-for="(price, index) in prices"
                :position="price.position"
            ></gmap-marker>
            </gmap-map>
        </div>
        <div>
            <table class="table-fixed">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Calle</th>
                        <th class="border px-4 py-2">Razón Social</th>
                        <th class="border px-4 py-2">RFC</th>
                        <th class="border px-4 py-2">Número de Permiso</th>
                        <th class="border px-4 py-2">Precio Regular</th>
                        <th class="border px-4 py-2">Precio Premium</th>
                        <th class="border px-4 py-2">Código Postal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(price, index) in prices" :key="price.id">
                        <td class="border px-4 py-2">{{ price.street }}</td>
                        <td class="border px-4 py-2">{{ price.business_name }}</td>
                        <td class="border px-4 py-2">{{ price.rfc }}</td>
                        <td class="border px-4 py-2">{{ price.permission_number }}</td>
                        <td class="border px-4 py-2">{{ price.regular }}</td>
                        <td class="border px-4 py-2">{{ price.premium }}</td>
                        <td class="border px-4 py-2">{{ price.zipcode }}</td>
                    </tr>                        
                </tbody>
            </table>
        
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.geolocate();
        },
        name: 'MapShops',
        data() {
            return {
                states: {},
                municipalities: {},
                prices: {},
                selectedState: '',
                selectedMunicipality: '',
                sort: '',
                center: { lat: 43.5293101, lng: -5.6773233 },
                boundMap: {},
            };
        },
        created() {
            this.getStates()
        },
        methods: {
            geolocate() {
                navigator.geolocation.getCurrentPosition(position => {
                    this.center = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                });
            },
            getStates() {
                axios.get('/api/v1/states')
                .then((response) => {
                    this.states = response.data.data;
                })
                .catch(function (error) {
                    console.log(error)
                });
            },
            getMunicipalities(event) {
                const state_id = event.target.value;
                axios.get(`/api/v1/states/${state_id}/municipalities`)
                .then((response) => {
                    this.municipalities = response.data.data;
                })
                .catch(function (error) {
                    console.log(error)
                });
            },
            filter(e) {
                e.preventDefault();
                const formData = new FormData()
                formData.append('state', this.selectedState);
                formData.append('municipality', this.selectedMunicipality);
                formData.append('sort', this.sort);

                const map = this.$refs.mapRef;

                const boundCoordinate = this.$refs.mapRef.$mapObject.getBounds();

                if(!boundCoordinate){return}
                const northEast = boundCoordinate.getNorthEast();
                const southWest = boundCoordinate.getSouthWest();
                const lats = [northEast.lat(), southWest.lat()];
                const lngs = [northEast.lng(), southWest.lng()];

                this.boundMap = {
                    top_x: _.max(lats),
                    bottom_x: _.min(lats),
                    top_y: _.max(lngs),
                    bottom_y: _.min(lngs)
                }

                axios.post('/api/v1/prices', formData)
                .then((response) => {
                    this.prices = response.data.data.map(price => {
                    return {
                        ...price, position: {
                        lat: parseFloat(price.latitude), lng: parseFloat(price.longitude)
                        }
                    }
                    }) 
                })
                .catch(function (error) {
                    console.log(error)
                });
            },
        },
    }
</script>
