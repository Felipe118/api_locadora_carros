<template>
    <div :class="estilo" role="alert">
       {{titulo}}
       <hr>
       <br>
       <span v-if="detalhes.data.message">{{detalhes.data.message}}</span>
       <span v-if="detalhes.data.id">{{'Marca '+detalhes.data.nome+' Cadastrada'}}</span>
       <ul v-if="detalhes.data.errors">
            <li v-for="erro, key in detalhes.data.errors" :key="key">{{erro[0]}}</li>
       </ul>
       {{detalhes.data.erros}}
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
