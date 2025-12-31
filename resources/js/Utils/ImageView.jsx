import React, { useState } from "react";

const ImageView = ({src, alt, style, className}) => {
   const [isZoomed, setIsZoomed] = useState(false);

   const toggleZoom = () => {
       setIsZoomed(!isZoomed);
   };

   return (
       <div className={`image-zoom-container`}>
           <img
               src={src}
               alt={alt}
               style={style}
               className={`image-zoom ${className} ${isZoomed ? "zoomed" : ""}`}
               onClick={toggleZoom}
           />
           {isZoomed && (
               <div
                   className="zoomed-image"
                   style={{ backgroundImage: `url(${src})` }}
                   onClick={toggleZoom}
               ></div>
           )}
       </div>
   );
};

export default ImageView;
