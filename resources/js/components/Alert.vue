<template>
    <div :class="estilo" role="alert">
       {{titulo}}
       <hr>
       <br>
       <p>{{ detalhes.message }}</p>
       <!-- <span v-if="detalhes.data.message">{{detalhes.data.message}}</span>
       <span v-if="detalhes.data.id">{{'Marca '+detalhes.data.nome+' Cadastrada'}}</span> -->
       <ul v-if="detalhes.datas">
            <li v-for="erro, key in detalhes.datas" :key="key">{{erro[0]}}</li>
       </ul>
    </div>
</template>

<script>
    export default {
      props: ['tipo','titulo','detalhes'],
      computed: {
          estilo(){
              return `alert alert-${this.tipo}`
          }
      }
    }
</script>
