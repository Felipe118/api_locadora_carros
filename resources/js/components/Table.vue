<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" v-for="t, key in titulos" :key="key" class="text-uppercase">{{t.titulo}}</th>
                 <th v-if="visualizar.visivel || atualizar || remover ">Ação</th>
            </tr>
              
        </thead>
        <tbody>
        <tr v-for="obj, chave in dadosFiltrados" :key="chave">
            <td v-for="valor, chaveValor in obj" :key="chaveValor">
              <span v-if="titulos[chaveValor].tipo == 'text'">{{valor}}</span>
              <span v-if="titulos[chaveValor].tipo == 'data'">{{'Data: '+valor}}</span>
              <span v-if="titulos[chaveValor].tipo == 'imagem'">
                 <img :src="'/storage/'+valor" width="40" height="40">
              </span>
            </td>
            <td v-if="visualizar.visivel || atualizar || remover "> 
                <button v-if="visualizar.visivel" :data-bs-target="visualizar.dataTarget"  :data-bs-toggle="visualizar.dataToggle" class="btn btn-outline-primary btn-sm">Visualizar</button>
                <button v-if="atualizar" class="btn btn-outline-primary btn-sm">Atualizar</button>
                <button v-if="remover" class="btn btn-outline-danger btn-sm">Excluir</button>
            </td>
        </tr>

        </tbody>
    </table>
</template>

<script>
    export default {
        props:['dados', 'titulos', 'atualizar','visualizar','remover'],
        computed: {
            dadosFiltrados(){
                let campos = Object.keys(this.titulos)
                let dadosFiltrados = []

                this.dados.map((item,chave) => {
                    
                    let itemFiltrado = {}
                    campos.forEach(campo => {
                        itemFiltrado[campo] = item[campo]
                        
                    })
                   dadosFiltrados.push(itemFiltrado )
                })
                return dadosFiltrados
            }
        }
    }
</script>
