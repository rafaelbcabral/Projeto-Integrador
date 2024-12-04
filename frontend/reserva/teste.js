function carregarMesas() {
  fetch("http://localhost:8000/mesas",{
    method: "GET",
    headers: {
      "Content-Type": "application/json",
    },
  } )
    .then((response) => {
      // Verifica se a resposta é bem-sucedida (status HTTP 200)
      if (!response.ok) {
        throw new Error("Erro na resposta da API");
      }
      return response.json(); // Converte a resposta para JSON
    })
    .then((mesas) => {
      console.log(mesas); // Exibe as mesas no console
      // Código para manipular a resposta da API
    })
    .catch((error) => {
      console.error("Erro ao carregar mesas:", error); // Exibe o erro no console, se houver
    });
}
