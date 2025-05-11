from fastapi import FastAPI, UploadFile, File
import faiss, torch, numpy as np
from mcam import AudioFingerprintModel, preprocess_audio

app = FastAPI()
# load model & FAISS index once
model = AudioFingerprintModel(fingerprint_dim=64).eval()
model.load_state_dict(torch.load("model.pt", map_location="cpu"))
index = faiss.read_index("fingerprint.index")

@app.post("/embed")
async def embed(file: UploadFile = File(...)):
    # read and preprocess
    wav, sr = await file.read(), 16000
    audio = preprocess_audio(wav, sr)
    with torch.no_grad():
        emb = model(audio.unsqueeze(0))  # shape [1,64]
    return {"embedding": emb[0].tolist()}

@app.post("/match")
async def match(req: dict):
    emb = np.array(req["embedding"], dtype="float32")[None, :]
    D, I = index.search(emb, k=5)
    return {"distances": D.tolist()[0], "indices": I.tolist()[0]}

