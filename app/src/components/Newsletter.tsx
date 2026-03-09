import { useState } from "react";
import { Mail } from "lucide-react";

export default function Newsletter() {
  const [email, setEmail] = useState("");

  return (
    <section className="mx-auto -mb-15 max-w-3/4 scale-z-100 rounded-full bg-[#111] py-13">
      <div className="mx-auto flex max-w-3/4 items-center justify-between gap-10 px-8">
        <h2 className="heading m-0 max-w-100 text-[36px] leading-[1.1] text-white">
          STAY UP TO DATE ABOUT OUR LATEST OFFERS
        </h2>
        <div className="flex w-[320px] shrink-0 flex-col gap-3">
          <div className="flex items-center gap-2.5 rounded-full bg-[#333] px-4.5 py-2.75">
            <Mail size={16} color="#bebebe" />
            <input
              type="email"
              placeholder="Enter your email address"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              className="w-full border-none bg-transparent text-[13px] text-[#ccc] outline-none placeholder:text-[#888]"
            />
          </div>
          <button className="cursor-pointer rounded-full border-none bg-white px-6 py-3.25 text-sm font-bold text-[#111] transition-colors hover:bg-[#eee]">
            Subscribe to Newsletter
          </button>
        </div>
      </div>
    </section>
  );
}
