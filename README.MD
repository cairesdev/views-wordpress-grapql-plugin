# 📊 GraphQL and Post Views

**Versão:** 1.2  
**Autor:** [cairesdev]  
**Descrição:**  
Este plugin adiciona suporte para rastreamento de visualizações de posts em WordPress. As visualizações são atualizadas via REST API e podem ser consultadas pela API GraphQL.

---

## 🔧 Funcionalidades

### 1. Consulta de Visualizações via GraphQL

Adiciona o campo `views` ao tipo `Post` na API GraphQL.

- **Campo:** `views`  
- **Tipo:** `Int`  
- **Descrição:** Número de visualizações do post

#### Exemplo de query:
```graphql
query {
  post(id: "meu-post", idType: SLUG) {
    title
    views
  }
}
````

---

### 2. Incremento de Visualizações via REST API

Exponibiliza uma rota REST para incrementar as visualizações de um post.

#### Endpoint:

```
POST /wp-json/views/v1/hit/{slug}
```

* **Parâmetro da URL:**
  `slug` – Slug do post (ex: `meu-post`)

* **Resposta de Sucesso (`200 OK`):**

```json
{
  "postId": 123,
  "slug": "meu-post",
  "views": 42
}
```

* **Erros Possíveis:**

  * `400 Bad Request` – Post inválido ou não publicado.

---

## 🛠️ Como Funciona

* Usa `graphql_register_types` para adicionar o campo `views` ao tipo `Post`.
* Usa `register_rest_route` para criar a rota REST `/views/v1/hit/{slug}`.
* A cada requisição POST, incrementa o campo `views` no meta do post.

---

## ✅ Permissões

* A rota REST está acessível publicamente (`__return_true`), sem necessidade de autenticação.

> ⚠️ **Atenção:** Considere proteger a rota se quiser evitar manipulação ou spam de visualizações.

---

## 📌 Requisitos

* WordPress com [WPGraphQL](https://www.wpgraphql.com/) instalado e ativo.
* Permalinks ativados para uso correto dos slugs.

---

## 💡 Dicas de Integração

* Faça uma chamada `POST` ao endpoint REST ao carregar uma página de post via JavaScript.
* Exemplo usando `fetch` no frontend:

```js
fetch('/wp-json/views/v1/hit/meu-post', {
  method: 'POST'
});
```

* Em temas com cache agressivo, certifique-se de que a chamada seja feita do lado do cliente para registrar corretamente as visualizações.

---

## 📃 Licença

Este plugin está licenciado sob a [MIT License](https://opensource.org/licenses/MIT).
