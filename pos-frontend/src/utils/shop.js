import axios from "axios";

let cachedShopId = null;

export async function getShopId() {

  if (cachedShopId)
    return cachedShopId;

  const res = await axios.get("/api/user/me");

  const shopId =
    res.data?.data?.shop_id ??
    res.data?.shop_id;

  if (!shopId)
    throw new Error("shop_id tidak ditemukan");

  cachedShopId = shopId;

  return shopId;
}