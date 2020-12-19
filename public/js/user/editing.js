const msg = document.getElementById("msg");
const video = document.querySelector("#videoElement");
const fileInput = document.getElementById('fileInput');
const canvas = document.getElementById('canvas');
const canvasWebcam = document.getElementById('canvas-webcam');
const option = document.getElementById('stickers-select');
const x = document.getElementById('x-axis');
const y = document.getElementById('y-axis');
const btn_capture = document.getElementById('btn-capture');
const btn_save = document.getElementById('btn-save');
const btn_cancel = document.getElementById('btn-cancel');
const form = document.getElementById('form-save');
const modelBG = document.querySelector('.model-bg');
const modelClose = document.querySelector('#icon-cancel');
const textarea = document.getElementById('textarea');
const description = document.getElementById('description');
let context = canvas.getContext('2d');
let contextWebcam = canvasWebcam.getContext('2d');
let base_image = new Image();

if ( navigator.mediaDevices.getUserMedia ) {
    navigator.mediaDevices.getUserMedia({ 'video': true })
    .then(( stream ) => { video.srcObject = stream; })
    .catch(( error ) => { console.log("If your camera doesn't work you can upload an image !"); });
}
const viewOption = ( option ) => {
    if ( option.value != "" && navigator.mediaDevices.getUserMedia ) {
        btn_capture.removeAttribute('disabled');
        fileInput.removeAttribute('disabled');
        base_image.src = option.value;
    } else {
        btn_capture.setAttribute('disabled', 'on');
        fileInput.setAttribute('disabled', 'on');
    }
}
// click on X and btn cancel with hide model
const closeModel = () => {
    modelBG.classList.remove('active-model');
    fileInput.value = "";
}
// click on btn capture will mix two images and display final image with options save or cancel
btn_capture.addEventListener('click', () => {
    contextWebcam.drawImage(video, 0, 0, 640, 480);
    context.drawImage(video, 0, 0, 640, 480);
    context.drawImage(base_image, x.value, y.value, 150, 120);
    modelBG.classList.add('active-model');
});
// Event listener for image upload 
fileInput.addEventListener('change', (e) => {
    if( e.target.files ) {
        let file = e.target.files[0];
        const fsize = e.target.files[0].size; 
        const sizefile = Math.round((fsize / 1024)); 
        
        // The size of the file. 
        if ( sizefile >= 4096 ) { 
            alert("File too Big, please select a file less than 4mb"); 
        } else {
            var reader  = new FileReader();
            reader.readAsDataURL( file );
            reader.onloadend = (e) => {
                var myImage = new Image(); // Creates image object
                myImage.src = e.target.result; // Assigns converted image to image object
                myImage.onload = (ev) => {
                    if ( ev.width > 640 || ev.height > 480 ) {
                        alert("Image must have 640X480"); 
                    } else {
                        contextWebcam.drawImage(myImage, 0, 0, 640, 480);
                        context.drawImage(myImage, 0, 0, 640, 480);
                        context.drawImage(base_image, x.value, y.value, 150, 120);
                        modelBG.classList.add('active-model');
                    }
                }
            }
        }
    }
});

setTimeout(() => {
    msg.innerHTML = "";
}, 4000);

form.addEventListener('submit', () => {
    let dataUrl = canvasWebcam.toDataURL();
    textarea.value = dataUrl;
    return true;
});