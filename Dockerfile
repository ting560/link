# Usa uma imagem base oficial do Node.js
FROM node:18

# Define o diretório de trabalho no container
WORKDIR /usr/src/app

# Copia os arquivos package.json e package-lock.json (se existir)
COPY package*.json ./

# Instala as dependências do projeto
RUN npm install

# Copia o resto do código da aplicação
COPY . .

# Expose a porta que a aplicação vai escutar (o Render usa a var PORT, mas é boa prática documentar)
# EXPOSE 3000

# Define o comando para iniciar a aplicação
CMD [ "node", "server.js" ]