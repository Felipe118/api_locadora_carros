<template>
    <table class="table table-hover">
        <thead>
            <tr>
            <th scope="col" v-for="t, key in titulos" :key="key"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="marcas in dados" :key="marcas.id">
            <th scope="row">{{marcas.id}}</th>
                <td>{{marcas.nome}}</td>
                <td> <img :src="'/storage/'+marcas.imagem" width="40" height="40" alt="Marcas"></td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props:['dados', 'titulos'],
    }
</script>
