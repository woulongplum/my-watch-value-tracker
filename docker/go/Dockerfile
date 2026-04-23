FROM golang:1.22-alpine

# ツールをインストール
RUN apk update && apk add --no-cache git

WORKDIR /app

# コンテナが勝手に終了しないようにする設定
CMD ["sh"]
